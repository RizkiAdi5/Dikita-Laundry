<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Service;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Services\BillPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'status'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($qc) use ($search) {
                      $qc->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('status', function($q) use ($request) {
                $q->where('name', $request->status);
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->paginate(12);

        $stats = [
            'today' => Order::whereDate('created_at', today())->count(),
            'in_progress' => Order::whereHas('status', fn($q) => $q->whereIn('name', ['Dalam Proses','Siap']))->count(),
            'completed_today' => Order::whereHas('status', fn($q) => $q->where('name', 'Selesai'))
                                      ->whereDate('updated_at', today())->count(),
            'delayed' => Order::whereHas('status', fn($q) => $q->whereNotIn('name', ['Selesai', 'Batal']))
                              ->whereNotNull('delivery_date')
                              ->whereDate('delivery_date', '<', today())
                              ->count(),
        ];

        $customers = Customer::select('id', 'name', 'phone')->orderBy('name')->get();
        $services = Service::active()->orderBy('sort_order')->get(['id','name','price','unit']);
        $statuses = OrderStatus::orderBy('sort_order')->get(['id','name','color','description']);

        return view('orders', compact('orders','stats','customers','services','statuses'));
    }

    public function create()
    {
        $customers = Customer::select('id', 'name', 'phone')->orderBy('name')->get();
        $services = Service::active()->orderBy('sort_order')->get(['id','name','price','unit']);
        return view('orders.create', compact('customers','services'));
    }

    public function show(Order $order)
    {
        $order->load(['customer','items.service','status']);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['items.service','customer']);
        $customers = Customer::select('id', 'name', 'phone')->orderBy('name')->get();
        $services = Service::active()->orderBy('sort_order')->get(['id','name','price','unit']);
        return view('orders.edit', compact('order','customers','services'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'order_status_id' => 'required|exists:order_statuses,id',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $oldStatus = $order->status;
            $newStatus = OrderStatus::find($request->order_status_id);

            $order->update([
                'order_status_id' => $request->order_status_id,
                'notes' => $request->notes ?? $order->notes,
            ]);

            // Log status change (optional - you can create a separate table for this)
            // OrderStatusHistory::create([...]);

            return response()->json([
                'success' => true,
                'message' => "Status berhasil diubah dari '{$oldStatus->name}' ke '{$newStatus->name}'",
                'data' => [
                    'order' => $order->load('status'),
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'pickup_date' => 'nullable|date',
            'delivery_date' => 'nullable|date|after_or_equal:pickup_date',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'payment_status' => 'nullable|in:unpaid,partial,paid',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.quantity' => 'required|numeric|min:0.1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            return DB::transaction(function() use ($request, $order) {
                $subtotal = 0;
                foreach ($request->items as $it) {
                    $subtotal += ((float)$it['unit_price']) * ((float)$it['quantity']);
                }
                $discount = (float)($request->discount ?? 0);
                $tax = (float)($request->tax ?? 0);
                $total = max(0, $subtotal - $discount + $tax);

                $order->update([
                    'customer_id' => $request->customer_id,
                    'pickup_date' => $request->pickup_date,
                    'delivery_date' => $request->delivery_date,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax' => $tax,
                    'total' => $total,
                    'paid_amount' => (float)($request->paid_amount ?? $order->paid_amount),
                    'payment_status' => $request->payment_status ?? $order->payment_status,
                    'notes' => $request->notes,
                ]);

                $order->items()->delete();
                foreach ($request->items as $it) {
                    $service = Service::find($it['service_id']);
                    $order->items()->create([
                        'service_id' => $it['service_id'],
                        'item_name' => $service ? $service->name : 'Layanan',
                        'quantity' => $it['quantity'],
                        'unit_price' => $it['unit_price'],
                        'total_price' => ((float)$it['unit_price']) * ((float)$it['quantity']),
                        'notes' => $it['notes'] ?? null,
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil diperbarui',
                    'data' => $order->load(['customer','items'])
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate pesanan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'pickup_date' => 'nullable|date',
            'delivery_date' => 'nullable|date|after_or_equal:pickup_date',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'payment_status' => 'nullable|in:unpaid,partial,paid',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.quantity' => 'required|numeric|min:0.1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            return DB::transaction(function() use ($request) {
                $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . str_pad((string) (Order::max('id') + 1), 4, '0', STR_PAD_LEFT);

                $subtotal = 0;
                foreach ($request->items as $it) {
                    $subtotal += ((float)$it['unit_price']) * ((float)$it['quantity']);
                }
                $discount = (float)($request->discount ?? 0);
                $tax = (float)($request->tax ?? 0);
                $total = max(0, $subtotal - $discount + $tax);

                $statusId = optional(OrderStatus::firstWhere('name', 'Menunggu'))?->id
                    ?? OrderStatus::firstOrCreate(['name' => 'Menunggu', 'color' => '#fbbf24', 'sort_order' => 1])->id;

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'customer_id' => $request->customer_id,
                    'order_status_id' => $statusId,
                    'pickup_date' => $request->pickup_date,
                    'delivery_date' => $request->delivery_date,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax' => $tax,
                    'total' => $total,
                    'paid_amount' => (float)($request->paid_amount ?? 0),
                    'payment_status' => $request->payment_status ?? 'unpaid',
                    'notes' => $request->notes,
                ]);

                foreach ($request->items as $it) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'service_id' => $it['service_id'],
                        'item_name' => Service::find($it['service_id'])->name,
                        'quantity' => $it['quantity'],
                        'unit_price' => $it['unit_price'],
                        'total_price' => ((float)$it['unit_price']) * ((float)$it['quantity']),
                        'notes' => $it['notes'] ?? null,
                    ]);
                }

                $paidAmount = (float)($request->paid_amount ?? 0);
                if ($paidAmount > 0) {
                    Payment::create([
                        'order_id' => $order->id,
                        'employee_id' => null,
                        'payment_number' => 'PAY-'.now()->format('Ymd').'-'.str_pad((string) (Payment::max('id') + 1), 4, '0', STR_PAD_LEFT),
                        'amount' => $paidAmount,
                        'payment_method' => $request->payment_method ?? 'cash',
                        'status' => $request->payment_status === 'paid' ? 'settled' : ($request->payment_status === 'partial' ? 'partial' : 'pending'),
                        'reference_number' => null,
                        'notes' => null,
                        'paid_at' => now(),
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibuat',
                    'data' => $order->load(['customer','items'])
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pesanan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function bill(Order $order, BillPdfService $pdfService)
    {
        $pdfBytes = $pdfService->render($order);
        return new StreamedResponse(function() use ($pdfBytes) {
            echo $pdfBytes;
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$order->order_number.'-bill.pdf"'
        ]);
    }

    public function destroy(Request $request, Order $order)
    {
        try {
            DB::transaction(function() use ($order) {
                $order->items()->delete();
                $order->payments()->delete();
                $order->delete();
            });

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Pesanan berhasil dihapus']);
            }
            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dihapus');
        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus pesanan', 'error' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal menghapus pesanan');
        }
    }
}
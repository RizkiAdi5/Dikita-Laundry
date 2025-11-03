<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Employee;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $period = $request->get('period', 'monthly'); // daily, weekly, monthly, yearly
        $date = $request->get('date', now()->format('Y-m-d'));
        
        // Calculate date range based on period
        $dateRange = $this->getDateRange($period, $date);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];
        
        // Previous period for comparison
        $prevDateRange = $this->getPreviousPeriodDateRange($period, $date);
        $prevStartDate = $prevDateRange['start'];
        $prevEndDate = $prevDateRange['end'];

        // Total Revenue
        $totalRevenue = (float) Payment::whereBetween('paid_at', [$startDate, $endDate])
            ->whereIn('status', ['settled', 'partial'])
            ->sum('amount');
        
        $prevTotalRevenue = (float) Payment::whereBetween('paid_at', [$prevStartDate, $prevEndDate])
            ->whereIn('status', ['settled', 'partial'])
            ->sum('amount');
        
        $revenueChange = $prevTotalRevenue > 0 
            ? (($totalRevenue - $prevTotalRevenue) / $prevTotalRevenue) * 100 
            : 0;

        // Total Orders
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $prevTotalOrders = Order::whereBetween('created_at', [$prevStartDate, $prevEndDate])->count();
        $ordersChange = $prevTotalOrders > 0 
            ? (($totalOrders - $prevTotalOrders) / $prevTotalOrders) * 100 
            : 0;

        // New Customers
        $newCustomers = Customer::whereBetween('created_at', [$startDate, $endDate])->count();
        $prevNewCustomers = Customer::whereBetween('created_at', [$prevStartDate, $prevEndDate])->count();
        $customersChange = $prevNewCustomers > 0 
            ? (($newCustomers - $prevNewCustomers) / $prevNewCustomers) * 100 
            : 0;

        // Revenue Chart Data (last 12 periods)
        $revenueChartData = $this->getRevenueChartData($period, $date);

        // Service Performance
        $servicePerformance = OrderItem::selectRaw('item_name, COUNT(*) as order_count, SUM(quantity) as total_quantity, SUM(total_price) as total_revenue')
            ->whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('item_name')
            ->orderByDesc('total_quantity')
            ->get();

        $totalOrdersForService = $servicePerformance->sum('order_count');
        $servicePerformance = $servicePerformance->map(function($service) use ($totalOrdersForService) {
            $service->percentage = $totalOrdersForService > 0 
                ? ($service->order_count / $totalOrdersForService) * 100 
                : 0;
            return $service;
        });

        // Top Customers
        $topCustomers = Customer::withCount(['orders' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withSum(['orders' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }], 'total')
            ->having('orders_count', '>', 0)
            ->orderByDesc('orders_sum_total')
            ->limit(5)
            ->get()
            ->map(function($customer) {
                $customer->order_count = $customer->orders_count;
                $customer->total_revenue = (float) ($customer->orders_sum_total ?? 0);
                return $customer;
            });

        // Inventory Status
        $inventoryStatus = Inventory::selectRaw('category, COUNT(*) as total_items, SUM(CASE WHEN quantity <= 0 THEN 1 ELSE 0 END) as out_of_stock, SUM(CASE WHEN quantity < min_quantity AND quantity > 0 THEN 1 ELSE 0 END) as low_stock, SUM(CASE WHEN quantity >= min_quantity THEN 1 ELSE 0 END) as in_stock')
            ->where('is_active', true)
            ->groupBy('category')
            ->get();

        // Individual inventory items for status display
        $inventoryItems = Inventory::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->map(function($item) {
                $item->stock_percentage = $item->min_quantity > 0 
                    ? min(100, ($item->quantity / max($item->min_quantity * 2, 1)) * 100)
                    : ($item->quantity > 0 ? 100 : 0);
                return $item;
            });

        // Employee Performance
        $employeePerformance = Employee::withCount(['orders' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->where('is_active', true)
            ->orderByDesc('orders_count')
            ->get()
            ->map(function($employee) {
                $employee->processed_orders = $employee->orders_count;
                return $employee;
            });

        return view('reports', [
            'period' => $period,
            'date' => $date,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalRevenue' => $totalRevenue,
            'prevTotalRevenue' => $prevTotalRevenue,
            'revenueChange' => $revenueChange,
            'totalOrders' => $totalOrders,
            'prevTotalOrders' => $prevTotalOrders,
            'ordersChange' => $ordersChange,
            'newCustomers' => $newCustomers,
            'prevNewCustomers' => $prevNewCustomers,
            'customersChange' => $customersChange,
            'revenueChartData' => $revenueChartData,
            'servicePerformance' => $servicePerformance,
            'topCustomers' => $topCustomers,
            'inventoryStatus' => $inventoryStatus,
            'inventoryItems' => $inventoryItems,
            'employeePerformance' => $employeePerformance,
        ]);
    }

    public function stock(Request $request)
    {
        $category = $request->get('category');
        $supplier = $request->get('supplier');

        $query = Inventory::query()->where('is_active', true);
        if ($category) {
            $query->where('category', $category);
        }
        if ($supplier) {
            $query->where('supplier', 'like', "%{$supplier}%");
        }

        $allItems = $query->orderBy('category')->orderBy('name')->get();

        $lowStockItems = $allItems->filter(function($i){ 
            return $i->quantity > 0 && $i->min_quantity > 0 && $i->quantity < $i->min_quantity; 
        });
        $outOfStockItems = $allItems->filter(function($i){ return $i->quantity <= 0; });
        $healthyItems = $allItems->filter(function($i){ 
            return $i->min_quantity > 0 && $i->quantity >= $i->min_quantity; 
        });

        $byCategory = $allItems->groupBy('category')->map(function($items, $cat){
            return [
                'category' => $cat ?: 'Tanpa Kategori',
                'total_items' => $items->count(),
                'in_stock' => $items->filter(function($i) { 
                    return $i->min_quantity > 0 && $i->quantity >= $i->min_quantity; 
                })->count(),
                'low_stock' => $items->filter(function($i) { 
                    return $i->quantity > 0 && $i->min_quantity > 0 && $i->quantity < $i->min_quantity; 
                })->count(),
                'out_of_stock' => $items->where('quantity', '<=', 0)->count(),
            ];
        })->values();

        // Recent inventory transactions (last 14 days)
        $recentTransactions = InventoryTransaction::with('inventory')
            ->whereDate('created_at', '>=', today()->subDays(14))
            ->latest('created_at')
            ->limit(50)
            ->get();

        $categories = Inventory::whereNotNull('category')->distinct()->pluck('category')->filter();
        $suppliers = Inventory::whereNotNull('supplier')->distinct()->pluck('supplier')->filter();

        return view('reports.stock', compact(
            'category', 'supplier', 'categories', 'suppliers', 'allItems', 'lowStockItems', 'outOfStockItems', 'healthyItems', 'byCategory', 'recentTransactions'
        ));
    }

    public function performance(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $date = $request->get('date', now()->format('Y-m-d'));
        $role = $request->get('role');
        $dateRange = $this->getDateRange($period, $date);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        // Employee performance
        $employeeQuery = Employee::query()->where('is_active', true);
        if ($role) {
            $employeeQuery->where('role', $role);
        }
        $employeePerformance = $employeeQuery->withCount(['orders' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->orderByDesc('orders_count')
            ->limit(25)
            ->get();

        // Service performance (top services)
        $servicePerformance = OrderItem::selectRaw('item_name, COUNT(*) as order_count, SUM(quantity) as total_quantity, SUM(total_price) as total_revenue')
            ->whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('item_name')
            ->orderByDesc('total_quantity')
            ->limit(25)
            ->get();

        // Order status distribution
        $statusDistribution = OrderStatus::withCount(['orders' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])->get(['id','name']);

        $roles = Employee::select('role')->whereNotNull('role')->distinct()->pluck('role')->filter();

        return view('reports.performance', compact(
            'period', 'date', 'role', 'roles', 'startDate', 'endDate', 'employeePerformance', 'servicePerformance', 'statusDistribution'
        ));
    }

    public function exportStockCsv(Request $request)
    {
        $request->merge(['per_page' => null]);
        $category = $request->get('category');
        $supplier = $request->get('supplier');

        $query = Inventory::query()->where('is_active', true);
        if ($category) $query->where('category', $category);
        if ($supplier) $query->where('supplier', 'like', "%{$supplier}%");

        $rows = $query->orderBy('category')->orderBy('name')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan_stok.csv"',
        ];

        $callback = function() use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Nama', 'SKU', 'Kategori', 'Supplier', 'Unit', 'Qty', 'Min Qty', 'Harga Pokok', 'Harga Jual']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->name,
                    $r->sku,
                    $r->category,
                    $r->supplier,
                    $r->unit,
                    (string)$r->quantity,
                    (string)$r->min_quantity,
                    (string)$r->cost_price,
                    (string)$r->selling_price,
                ]);
            }
            fclose($out);
        };

        return response()->streamDownload($callback, 'laporan_stok.csv', $headers);
    }

    public function exportPerformanceCsv(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $date = $request->get('date', now()->format('Y-m-d'));
        $role = $request->get('role');
        $dateRange = $this->getDateRange($period, $date);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        $empQuery = Employee::query()->where('is_active', true);
        if ($role) $empQuery->where('role', $role);
        $employees = $empQuery->withCount(['orders' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        }])->orderByDesc('orders_count')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan_performa.csv"',
        ];

        $callback = function() use ($employees) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Nama', 'Role', 'Posisi', 'Pesanan Diproses', 'Telepon']);
            foreach ($employees as $e) {
                fputcsv($out, [
                    $e->name,
                    $e->role,
                    $e->position,
                    (string)$e->orders_count,
                    $e->phone,
                ]);
            }
            fclose($out);
        };

        return response()->streamDownload($callback, 'laporan_performa.csv', $headers);
    }

    private function getDateRange($period, $date)
    {
        $dateObj = Carbon::parse($date);
        
        switch ($period) {
            case 'daily':
                return [
                    'start' => $dateObj->copy()->startOfDay(),
                    'end' => $dateObj->copy()->endOfDay(),
                ];
            case 'weekly':
                return [
                    'start' => $dateObj->copy()->startOfWeek(),
                    'end' => $dateObj->copy()->endOfWeek(),
                ];
            case 'monthly':
                return [
                    'start' => $dateObj->copy()->startOfMonth(),
                    'end' => $dateObj->copy()->endOfMonth(),
                ];
            case 'yearly':
                return [
                    'start' => $dateObj->copy()->startOfYear(),
                    'end' => $dateObj->copy()->endOfYear(),
                ];
            default:
                return [
                    'start' => $dateObj->copy()->startOfMonth(),
                    'end' => $dateObj->copy()->endOfMonth(),
                ];
        }
    }

    private function getPreviousPeriodDateRange($period, $date)
    {
        $dateObj = Carbon::parse($date);
        
        switch ($period) {
            case 'daily':
                return [
                    'start' => $dateObj->copy()->subDay()->startOfDay(),
                    'end' => $dateObj->copy()->subDay()->endOfDay(),
                ];
            case 'weekly':
                return [
                    'start' => $dateObj->copy()->subWeek()->startOfWeek(),
                    'end' => $dateObj->copy()->subWeek()->endOfWeek(),
                ];
            case 'monthly':
                return [
                    'start' => $dateObj->copy()->subMonth()->startOfMonth(),
                    'end' => $dateObj->copy()->subMonth()->endOfMonth(),
                ];
            case 'yearly':
                return [
                    'start' => $dateObj->copy()->subYear()->startOfYear(),
                    'end' => $dateObj->copy()->subYear()->endOfYear(),
                ];
            default:
                return [
                    'start' => $dateObj->copy()->subMonth()->startOfMonth(),
                    'end' => $dateObj->copy()->subMonth()->endOfMonth(),
                ];
        }
    }

    private function getRevenueChartData($period, $date)
    {
        $dateObj = Carbon::parse($date);
        $periods = 12;
        $data = [];

        switch ($period) {
            case 'daily':
                // Last 14 days
                for ($i = 13; $i >= 0; $i--) {
                    $d = $dateObj->copy()->subDays($i);
                    $amount = (float) Payment::whereDate('paid_at', $d)
                        ->whereIn('status', ['settled', 'partial'])
                        ->sum('amount');
                    $data[] = [
                        'label' => $d->format('d M'),
                        'amount' => $amount,
                    ];
                }
                break;
            case 'weekly':
                // Last 12 weeks
                for ($i = 11; $i >= 0; $i--) {
                    $w = $dateObj->copy()->subWeeks($i);
                    $start = $w->copy()->startOfWeek();
                    $end = $w->copy()->endOfWeek();
                    $amount = (float) Payment::whereBetween('paid_at', [$start, $end])
                        ->whereIn('status', ['settled', 'partial'])
                        ->sum('amount');
                    $data[] = [
                        'label' => 'Minggu ' . $w->format('W/Y'),
                        'amount' => $amount,
                    ];
                }
                break;
            case 'monthly':
                // Last 12 months
                for ($i = 11; $i >= 0; $i--) {
                    $m = $dateObj->copy()->subMonths($i);
                    $start = $m->copy()->startOfMonth();
                    $end = $m->copy()->endOfMonth();
                    $amount = (float) Payment::whereBetween('paid_at', [$start, $end])
                        ->whereIn('status', ['settled', 'partial'])
                        ->sum('amount');
                    $data[] = [
                        'label' => $m->format('M Y'),
                        'amount' => $amount,
                    ];
                }
                break;
            case 'yearly':
                // Last 5 years
                for ($i = 4; $i >= 0; $i--) {
                    $y = $dateObj->copy()->subYears($i);
                    $start = $y->copy()->startOfYear();
                    $end = $y->copy()->endOfYear();
                    $amount = (float) Payment::whereBetween('paid_at', [$start, $end])
                        ->whereIn('status', ['settled', 'partial'])
                        ->sum('amount');
                    $data[] = [
                        'label' => $y->format('Y'),
                        'amount' => $amount,
                    ];
                }
                break;
        }

        return $data;
    }
}


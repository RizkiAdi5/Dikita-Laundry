<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['employee', 'approver']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('expense_number', 'like', "%{$search}%")
                  ->orWhere('supplier', 'like', "%{$search}%")
                  ->orWhere('receipt_number', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $expenses = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $stats = $this->getExpenseStats();

        return view('expenses', compact('expenses', 'stats'));
    }

    public function create()
    {
        $employees = Employee::where('is_active', true)->get();
        return view('expenses.create', compact('employees'));
    }

    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:operational,utilities,salary,inventory,equipment,maintenance,marketing,rent,insurance,tax,other',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,card,check,other',
            'status' => 'required|in:pending,approved,rejected,paid',
            'expense_date' => 'required|date',
            'supplier' => 'nullable|string|max:255',
        ]);

        try {
            // Prepare data with defaults
            $data = $validated;
            $data['frequency'] = 'one_time';
            $data['is_recurring'] = false;
            $data['is_approved'] = false;

            $expense = Expense::create($data);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengeluaran berhasil ditambahkan',
                    'data' => $expense
                ]);
            }

            return redirect()->route('expenses.index')
                           ->with('success', '✅ Pengeluaran "' . $expense->title . '" berhasil ditambahkan dengan nomor ' . $expense->expense_number);

        } catch (\Exception $e) {
            \Log::error('Expense creation error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                        ->with('error', '❌ Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function show(Expense $expense)
    {
        $expense->load(['employee', 'approver']);
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $employees = Employee::where('is_active', true)->get();
        return view('expenses.edit', compact('expense', 'employees'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:operational,utilities,salary,inventory,equipment,maintenance,marketing,rent,insurance,tax,other',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,card,check,other',
            'status' => 'required|in:pending,approved,rejected,paid',
            'frequency' => 'nullable|in:one_time,daily,weekly,monthly,yearly',
            'employee_id' => 'nullable|exists:employees,id',
            'receipt_number' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'expense_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:expense_date',
            'paid_date' => 'nullable|date|after_or_equal:expense_date',
            'notes' => 'nullable|string',
            'is_recurring' => 'boolean',
            'is_approved' => 'boolean'
        ]);

        try {
            // Prepare data with defaults
            $data = $request->all();
            $data['frequency'] = $data['frequency'] ?? 'one_time';
            $data['is_recurring'] = $request->has('is_recurring');
            $data['is_approved'] = $request->has('is_approved');

            $expense->update($data);

            // If status is paid, set paid_date
            if ($request->status === 'paid' && !$request->paid_date) {
                $expense->update(['paid_date' => now()]);
            }

            // If approved, set approved_by
            if ($request->status === 'approved' && !$request->approved_by) {
                $expense->update(['approved_by' => auth()->id() ?? 1]);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengeluaran berhasil diperbarui',
                    'data' => $expense
                ]);
            }

            return redirect()->route('expenses.index')
                           ->with('success', '✅ Pengeluaran "' . $expense->title . '" berhasil diperbarui');

        } catch (\Exception $e) {
            \Log::error('Expense update error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                        ->with('error', '❌ Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(Expense $expense)
    {
        try {
            $expenseTitle = $expense->title;
            $expenseNumber = $expense->expense_number;
            
            $expense->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengeluaran berhasil dihapus'
                ]);
            }

            return redirect()->route('expenses.index')
                           ->with('success', '✅ Pengeluaran "' . $expenseTitle . '" (' . $expenseNumber . ') berhasil dihapus');

        } catch (\Exception $e) {
            \Log::error('Expense deletion error: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data'
                ], 500);
            }

            return back()->with('error', '❌ Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function approve(Expense $expense)
    {
        try {
            $expense->update([
                'status' => 'approved',
                'is_approved' => true,
                'approved_by' => auth()->id() ?? 1
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengeluaran berhasil disetujui'
                ]);
            }

            return back()->with('success', '✅ Pengeluaran "' . $expense->title . '" berhasil disetujui');

        } catch (\Exception $e) {
            \Log::error('Expense approval error: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyetujui pengeluaran'
                ], 500);
            }

            return back()->with('error', '❌ Terjadi kesalahan saat menyetujui pengeluaran: ' . $e->getMessage());
        }
    }

    public function reject(Expense $expense)
    {
        try {
            $expense->update([
                'status' => 'rejected',
                'is_approved' => false
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengeluaran berhasil ditolak'
                ]);
            }

            return back()->with('success', '✅ Pengeluaran "' . $expense->title . '" berhasil ditolak');

        } catch (\Exception $e) {
            \Log::error('Expense rejection error: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menolak pengeluaran'
                ], 500);
            }

            return back()->with('error', '❌ Terjadi kesalahan saat menolak pengeluaran: ' . $e->getMessage());
        }
    }

    public function markAsPaid(Expense $expense)
    {
        try {
            $expense->update([
                'status' => 'paid',
                'paid_date' => now()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengeluaran berhasil ditandai sebagai dibayar'
                ]);
            }

            return back()->with('success', '✅ Pengeluaran "' . $expense->title . '" berhasil ditandai sebagai dibayar');

        } catch (\Exception $e) {
            \Log::error('Expense mark as paid error: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menandai pengeluaran'
                ], 500);
            }

            return back()->with('error', '❌ Terjadi kesalahan saat menandai pengeluaran: ' . $e->getMessage());
        }
    }

    private function getExpenseStats()
    {
        $currentMonth = now()->format('Y-m');
        $lastMonth = now()->subMonth()->format('Y-m');

        $currentMonthExpenses = Expense::whereYear('expense_date', now()->year)
                                     ->whereMonth('expense_date', now()->month)
                                     ->sum('amount');

        $lastMonthExpenses = Expense::whereYear('expense_date', now()->subMonth()->year)
                                  ->whereMonth('expense_date', now()->subMonth()->month)
                                  ->sum('amount');

        $pendingExpenses = Expense::where('status', 'pending')->sum('amount');
        $overdueExpenses = Expense::overdue()->sum('amount');

        $categoryStats = Expense::select('category', DB::raw('SUM(amount) as total'))
                               ->whereYear('expense_date', now()->year)
                               ->whereMonth('expense_date', now()->month)
                               ->groupBy('category')
                               ->get()
                               ->pluck('total', 'category')
                               ->toArray();

        return [
            'total_this_month' => $currentMonthExpenses,
            'total_last_month' => $lastMonthExpenses,
            'pending_amount' => $pendingExpenses,
            'overdue_amount' => $overdueExpenses,
            'category_stats' => $categoryStats,
            'growth_percentage' => $lastMonthExpenses > 0 ? 
                (($currentMonthExpenses - $lastMonthExpenses) / $lastMonthExpenses) * 100 : 0
        ];
    }
} 
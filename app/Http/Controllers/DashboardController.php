<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\OrderStatus;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Totals
        $totalOrders = Order::count();
        $activeCustomers = Customer::count();

        $revenueToday = Payment::whereDate('paid_at', today())
            ->whereIn('status', ['settled', 'partial'])
            ->sum('amount');

        $pendingOrders = Order::whereHas('status', function($q){
            $q->whereNotIn('name', ['completed']);
        })->count();

        // Status breakdown
        $statusCounts = OrderStatus::withCount('orders')->get(['id','name']);

        // Sales series (last 14 days)
        $days = 14;
        $salesSeries = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $amount = (float) Payment::whereDate('paid_at', $date)
                ->whereIn('status', ['settled','partial'])
                ->sum('amount');
            $salesSeries[] = [
                'date' => $date->format('Y-m-d'),
                'amount' => $amount,
            ];
        }

        // Recent orders
        $recentOrders = Order::with(['customer','status'])
            ->latest()
            ->limit(10)
            ->get();

        // Top services (last 30 days by quantity)
        $topServices = OrderItem::selectRaw('item_name, SUM(quantity) as total_qty, SUM(total_price) as total_revenue')
            ->whereHas('order', function($q){
                $q->whereDate('created_at', '>=', today()->subDays(30));
            })
            ->groupBy('item_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('index', [
            'totalOrders' => $totalOrders,
            'activeCustomers' => $activeCustomers,
            'revenueToday' => $revenueToday,
            'pendingOrders' => $pendingOrders,
            'statusCounts' => $statusCounts,
            'salesSeries' => $salesSeries,
            'recentOrders' => $recentOrders,
            'topServices' => $topServices,
        ]);
    }
}



<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ============================================================================
// GUEST ROUTES (Tidak perlu login)
// ============================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ============================================================================
// AUTHENTICATED ROUTES (Harus login)
// ============================================================================
Route::middleware(['auth'])->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ========================================================================
    // DASHBOARD - Semua role bisa akses
    // ========================================================================
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // ========================================================================
    // ORDERS - super_admin, admin, manager, cashier, operator, staff
    // ========================================================================
    Route::middleware(['role:super_admin,admin,manager,cashier,operator,staff'])->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create')->middleware('permission:orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store')->middleware('permission:orders.create');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit')->middleware('permission:orders.edit');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update')->middleware('permission:orders.edit');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy')->middleware('permission:orders.delete');
        Route::get('/orders/{order}/bill', [OrderController::class, 'bill'])->name('orders.bill');
        Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->name('orders.updateStatus')
        ->middleware('permission:orders.edit');
        Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    });

    // ========================================================================
    // CUSTOMERS - super_admin, admin, manager, cashier, staff
    // ========================================================================
    Route::middleware(['role:super_admin,admin,manager,cashier,staff'])->group(function () {
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create')->middleware('permission:customers.create');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store')->middleware('permission:customers.create');
        Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit')->middleware('permission:customers.edit');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update')->middleware('permission:customers.edit');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy')->middleware('permission:customers.delete');
        
        // API routes for customer
        Route::get('/api/customers/stats', [CustomerController::class, 'getStats'])->name('customers.stats');
        Route::get('/api/customers/search', [CustomerController::class, 'search'])->name('customers.search');
    });

    // ========================================================================
    // SERVICES - super_admin, admin, manager, staff
    // ========================================================================
    Route::middleware(['role:super_admin,admin,manager,staff'])->group(function () {
        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
        
        // Create, Edit, Delete hanya untuk super_admin, admin, manager
        Route::middleware(['permission:services.create'])->group(function () {
            Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
            Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
        });
        
        Route::middleware(['permission:services.edit'])->group(function () {
            Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
            Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
            Route::patch('/api/services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
        });
        
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy')->middleware('permission:services.delete');
        
        // API routes for service
        Route::get('/api/services/stats', [ServiceController::class, 'getStats'])->name('services.stats');
        Route::get('/api/services/search', [ServiceController::class, 'search'])->name('services.search');
    });

    // ========================================================================
    // INVENTORY - super_admin, admin, manager, operator, staff
    // ========================================================================
    Route::middleware(['role:super_admin,admin,manager,operator,staff'])->group(function () {
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/{inventory}', [InventoryController::class, 'show'])->name('inventory.show');
        
        // Create, Edit, Delete hanya untuk super_admin, admin, manager, operator
        Route::middleware(['permission:inventory.create'])->group(function () {
            Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
            Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
        });
        
        Route::middleware(['permission:inventory.edit'])->group(function () {
            Route::get('/inventory/{inventory}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
            Route::put('/inventory/{inventory}', [InventoryController::class, 'update'])->name('inventory.update');
        });
        
        Route::delete('/inventory/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy')->middleware('permission:inventory.delete');
        
        // API routes for inventory
        Route::get('/api/inventory/stats', [InventoryController::class, 'getStats'])->name('inventory.stats');
        Route::get('/api/inventory/search', [InventoryController::class, 'search'])->name('inventory.search');
    });

    // ========================================================================
    // EXPENSES - super_admin, admin, manager, staff
    // ========================================================================
    Route::middleware(['role:super_admin,admin,manager,staff'])->group(function () {
        Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
        
        // Create
        Route::middleware(['permission:expenses.create'])->group(function () {
            Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
            Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
        });
        
        // Edit dan Delete hanya untuk super_admin, admin, manager
        Route::middleware(['permission:expenses.edit'])->group(function () {
            Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
            Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
        });
        
        Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy')->middleware('permission:expenses.delete');
        
        // Approve/Reject hanya untuk super_admin, admin, manager
        Route::middleware(['permission:expenses.approve'])->group(function () {
            Route::post('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
            Route::post('/expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
            Route::post('/expenses/{expense}/mark-as-paid', [ExpenseController::class, 'markAsPaid'])->name('expenses.mark-as-paid');
        });
    });

    // ========================================================================
    // EMPLOYEES - super_admin, admin ONLY
    // ========================================================================
    Route::middleware(['role:super_admin,admin'])->group(function () {
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
        Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        
        // API routes for employee
        Route::get('/api/employees/stats', [EmployeeController::class, 'getStats'])->name('employees.stats');
        Route::get('/api/employees/search', [EmployeeController::class, 'search'])->name('employees.search');
        Route::patch('/api/employees/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
    });

    // ========================================================================
    // REPORTS - super_admin, admin, manager ONLY
    // ========================================================================
    Route::middleware(['role:super_admin,admin,manager'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
        Route::get('/reports/performance', [ReportController::class, 'performance'])->name('reports.performance');
        Route::get('/reports/stock/export', [ReportController::class, 'exportStockCsv'])->name('reports.stock.export');
        Route::get('/reports/performance/export', [ReportController::class, 'exportPerformanceCsv'])->name('reports.performance.export');
    });
});
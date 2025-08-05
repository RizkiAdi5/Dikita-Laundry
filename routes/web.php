<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;

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

Route::get('/', function () {
    return view('index');
});

Route::get('/orders', function () {
    return view('orders');
});

// Customer routes - Perbaiki urutan untuk menghindari konflik routing
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');

// API routes for customer
Route::get('/api/customers/stats', [CustomerController::class, 'getStats'])->name('customers.stats');
Route::get('/api/customers/search', [CustomerController::class, 'search'])->name('customers.search');

// Service routes
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

// API routes for service
Route::get('/api/services/stats', [ServiceController::class, 'getStats'])->name('services.stats');
Route::get('/api/services/search', [ServiceController::class, 'search'])->name('services.search');
Route::patch('/api/services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');

// Employee routes
Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');

// API routes for employee
Route::get('/api/employees/stats', [EmployeeController::class, 'getStats'])->name('employees.stats');
Route::get('/api/employees/search', [EmployeeController::class, 'search'])->name('employees.search');
Route::patch('/api/employees/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');

// Expense routes
Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');

// API routes for expense
Route::post('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
Route::post('/expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
Route::post('/expenses/{expense}/mark-as-paid', [ExpenseController::class, 'markAsPaid'])->name('expenses.mark-as-paid');

Route::get('/inventory', function () {
    return view('inventory');
});



Route::get('/monitoring', function () {
    return view('monitoring');
});

Route::get('/reports', function () {
    return view('reports');
});



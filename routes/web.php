<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;

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

Route::get('/inventory', function () {
    return view('inventory');
});

Route::get('/employees', function () {
    return view('employees');
});

Route::get('/monitoring', function () {
    return view('monitoring');
});

Route::get('/reports', function () {
    return view('reports');
});



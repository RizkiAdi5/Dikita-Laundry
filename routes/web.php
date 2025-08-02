<?php

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

Route::get('/', function () {
    return view('index');
});

Route::get('/orders', function () {
    return view('orders');
});

Route::get('/customers', function () {
    return view('customers');
});

Route::get('/services', function () {
    return view('services');
});

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



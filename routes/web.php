<?php

namespace App\Http\Controllers;
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
    return view('home');
})->name('home');

Route::resource('category', CategoryController::class);
Route::resource('size', SizeController::class);
Route::resource('color', ColorController::class);
Route::resource('product', ProductController::class);

Route::get('pos', [CartController::class, 'index'])->name('pos');
Route::get('order/history', [OrderController::class, 'index'])->name('order.list');
Route::get('order/{order}/items', [OrderController::class, 'orderItems'])->name('order.list.items');

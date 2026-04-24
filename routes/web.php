<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FirstController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

// --- Public Routes (Bisa diakses siapa saja) ---
Route::get('/', function () {
    return view('welcome');
});

Route::get('/hi', function(){
    return 'hi';
})->name('hi');

Route::get('/helo', function(){
    return 'helooo isb';
})->name('helo');

Route::get('/about', function(){
    return view('about');
})->name('about');


// --- Auth & Guest Routes ---
Route::get('/login', [AuthController::class, 'show_login'])->name('login.show')->middleware('guest');
Route::post('/login.auth', [AuthController::class, 'login_auth'])->name('login.auth');


// --- Protected Routes (Harus Login / Middleware Auth) ---
Route::middleware('auth')->group(function() {
    
    // Home Routes
    Route::get('/home', [HomeController::class, 'show'])->name('home');
    Route::get('/home/sum', [FirstController::class, 'sum'])->name('home.sum');
    Route::get('/home/multiply/{param1}/{param2?}', [FirstController::class, 'multiply'])->name('home.multiply');

    // --- Role: Admin & Owner Only ---
    Route::middleware(['role:admin,owner'])->group(function() {
        Route::get('/product/insert-form', [StoreController::class, 'product_insert_form'])->name('product_insert_form');
        Route::post('/product/insert', [StoreController::class, 'insert_product'])->name('insert_product');
        
        Route::get('/product/edit/{product_id}', [StoreController::class, 'product_edit_form'])->name('product_edit_form');
        Route::put('/product/edit/{product_id}', [StoreController::class, 'update_product'])->name('update_product');
        Route::delete('/product/delete/{product_id}', [StoreController::class, 'delete_product'])->name('delete_product');
    });

    // --- Role: Customer, Admin, & Owner ---
    Route::middleware(['role:customer,admin,owner'])->group(function() {
        Route::get('/store', [StoreController::class, 'show'])->name('store');
        
        // --- Midtrans Routes (Sesuai Foto PPT) ---
        // Midtrans Auto-Check Status After Return
        Route::get('/payment/return/{order_id}', [StoreController::class, 'payment_return'])->name('payment_return');
        // Midtrans Manual Check Status
        Route::get('/payment/status/{order_id}', [StoreController::class, 'payment_status'])->name('payment_status');
    });

    // Cart & Checkout Routes
    Route::post('/add-to-cart/{product_id}', [StoreController::class, 'add_to_cart'])->name('add_to_cart');
    Route::get('/view-cart',[StoreController::class, 'view_cart'])->name('view_cart');
    Route::patch('/update-cart/{product_id}',[StoreController::class, 'update_cart'])->name('update_cart');
    Route::delete('/remove-from-cart/{product_id}',[StoreController::class, 'remove_from_cart'])->name('remove_from_cart');
    Route::post('/checkout',[StoreController::class, 'checkout'])->name('checkout');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::middleware(['role:customer,admin,owner'])->group(function() {
    Route::get('/store', [StoreController::class, 'show'])->name('store');
    
    // Tambahkan route ini (Sesuai PPT)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');

    // Midtrans Routes
    Route::get('/payment/return/{order_id}', [StoreController::class, 'payment_return'])->name('payment_return');
    Route::get('/payment/status/{order_id}', [StoreController::class, 'payment_status'])->name('payment_status');
});

Route::get('/order/{order_id}', [OrderController::class, 'order_details'])->name('order_details');


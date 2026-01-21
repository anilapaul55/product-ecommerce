<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Models\Product;


// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    $products = Product::latest()->get(); // or paginate(8)
    return view('welcome', compact('products'));
});

// Route::get('/userdashboard', function () {
//     return view('user.dashboard');
// })->middleware(['auth', 'verified', 'role:user'])->name('udashboard');

Route::get('/userdashboard', [ShopController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:user'])
    ->name('udashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');

Route::middleware('auth', 'role:admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    Route::resource('products', ProductController::class);
    Route::get('/products/import', [ProductImportController::class, 'showImportForm'])->name('products.import.form');
    Route::post('/products/import', [ProductImportController::class, 'import'])->name('products.import');
});

Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply.coupon');
Route::post('/cart/ajax-update/{id}', [CartController::class, 'ajaxUpdate'])
    ->name('cart.ajax.update');

Route::post('/cart/ajax-apply-coupon', [CartController::class, 'ajaxApplyCoupon'])
    ->name('cart.ajax.apply.coupon');

Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');


require __DIR__.'/auth.php';

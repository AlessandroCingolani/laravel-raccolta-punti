<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\GiftVouchersController;
use App\Http\Controllers\ProfileController;
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
    return redirect('login');
});

Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('customers', CustomerController::class);
        Route::resource('purchases', PurchaseController::class);
        Route::resource('gift_vouchers', GiftVouchersController::class);


        // route custome
        Route::get('customers/{direction}/{column}', [CustomerController::class, 'orderBy'])->name('order-by');
        Route::get('/search-customer', [CustomerController::class, 'searchCustomer'])->name('search-customer');
        Route::get('/filtered-customers', [CustomerController::class, 'filterCustomer'])->name('filtered-customers');
        Route::post('/print-coupon', [CustomerController::class, 'printCoupon'])->name('print-coupon');
        // send email route
        Route::post('/send-email', [EmailController::class, 'send'])->name('send-email');
        // route to take who used coupons
        Route::get('coupons-used', [PurchaseController::class, 'couponsUsed'])->name('coupons-used');
        // route to take used Gift Vouchers
        Route::get('gift-used', [GiftVouchersController::class, 'usedGift'])->name('gift-used');
        // route to take expired Gift Vouchers
        Route::get('gift-expired', [GiftVouchersController::class, 'expiredGift'])->name('gift-expired');
        // search customers with gift voucher
        Route::get('/search-gift-customers', [GiftVouchersController::class, 'searchGiftCustomers'])->name('search-gift-customers');
    });

require __DIR__ . '/auth.php';
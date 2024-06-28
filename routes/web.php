<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PurchaseController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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


        // route custome
        Route::get('customers/{direction}/{column}', [CustomerController::class, 'orderBy'])->name('order-by');
        Route::get('admin/search-customer', [CustomerController::class, 'searchCustomer'])->name('search-customer');

        // Route::get('purchases/create/{id}', [PurchaseController::class, 'clientPurchase'])->name('selected-client');
    });

require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('logout', [LoginController::class, 'logout']);

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resource('users', UserController::class)->except(['edit', 'create']);
Route::resource('products', ProductController::class)->except(['edit', 'create']);
Route::resource('vendors', VendorController::class)->except(['edit', 'create']);

Route::post('/purchases-import', [PurchaseController::class, 'import'])->name('purchases.import');
Route::resource('purchases', PurchaseController::class)->except(['edit', 'create']);
Route::resource('purchase-items', PurchaseItemController::class)->except(['edit', 'create']);
Route::resource('barcodes', BarcodeController::class)->except(['edit', 'create']);

Route::get('/barcode-scan', [BarcodeController::class, 'scan'])->name('barcodes.scan');
Route::get('/barcode-get', [BarcodeController::class, 'get'])->name('barcodes.get');

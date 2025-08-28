<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarcodeActivityController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\OutboundItemController;
use App\Http\Controllers\OutboundScanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseItemController;
use App\Http\Controllers\PurchaseScanController;
use App\Http\Controllers\PurchaseTransactionController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});


Auth::routes([
    'register'  => false,
    'verify'    => false,
    'reset'     => false,
]);
Route::group(['middleware' => ['auth']], function () {
    Route::get('logout', [LoginController::class, 'logout']);

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('users', UserController::class)->except(['edit', 'create']);
    Route::resource('products', ProductController::class)->except(['edit', 'create']);
    Route::resource('vendors', VendorController::class)->except(['edit', 'create']);
    Route::resource('sections', SectionController::class)->except(['edit', 'create']);

    Route::get('/purchases/{purchase}/scan', [PurchaseScanController::class, 'index'])->name('purchases.scan');
    Route::post('/purchases/{purchase}/scan', [PurchaseScanController::class, 'save'])->name('purchases.save');
    Route::post('/purchases-import', [PurchaseController::class, 'import'])->name('purchases.import');
    Route::resource('purchases', PurchaseController::class)->except(['edit', 'create']);
    Route::resource('purchase-items', PurchaseItemController::class)->except(['edit', 'create']);
    Route::resource('barcodes', BarcodeController::class)->except(['edit', 'create']);

    Route::get('/barcode-scan', [BarcodeController::class, 'scan'])->name('barcodes.scan');
    Route::get('/barcode-get', [BarcodeController::class, 'get'])->name('barcodes.get');

    Route::resource('barcode-activities', BarcodeActivityController::class)->only(['index']);

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/purchase-trxs', [PurchaseTransactionController::class, 'index'])->name('purchase-trx.index');

    Route::get('/outbounds/{outbound}/scan', [OutboundScanController::class, 'get'])->name('outbounds.scan');
    Route::resource('outbounds', OutboundController::class)->except(['edit', 'create']);
    Route::resource('outbound-items', OutboundItemController::class)->except(['edit', 'create']);
});

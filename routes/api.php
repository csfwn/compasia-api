<?php

use App\Http\Controllers\ProductMasterListController;
use Illuminate\Support\Facades\Route;

Route::prefix('product-master-lists')->name('product-master-lists.')->group(function () {
    Route::get('/', [ProductMasterListController::class, 'index']);
    Route::post('/upload', [ProductMasterListController::class, 'upload']);
});

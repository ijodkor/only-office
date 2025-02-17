<?php

use Illuminate\Support\Facades\Route;
use OnlyOffice\Http\Controllers\Api\CallbackController;
use OnlyOffice\Http\Controllers\Api\CommandController;
use OnlyOffice\Http\Controllers\OfficeDocumentController;

Route::prefix('/api')->group(function () {
    Route::get('/template/download', [OfficeDocumentController::class, 'download'])->name('template.download');

    Route::prefix('/office')->group(function() {
        Route::get('/file/download', [OfficeDocumentController::class, 'download'])->name('office.file.download');
    });

    /*** Callback ***/
    Route::prefix('/template/document')->group(function () {
        Route::post('/callback', [CallbackController::class, 'handle'])->name('only-office.callback');
        Route::post('/store', [CommandController::class, 'command'])->name('only-office.command');
    });
});

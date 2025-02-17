<?php

use Illuminate\Support\Facades\Route;
use OnlyOffice\Http\Controllers\WorkspaceController;

Route::prefix('/office')->group(function() {
    Route::get('/workspace', [WorkspaceController::class, 'index']);
});
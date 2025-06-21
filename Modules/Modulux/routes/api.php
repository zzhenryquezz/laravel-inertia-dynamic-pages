<?php

use Illuminate\Support\Facades\Route;
use Modules\Modulux\Http\Controllers\ModuluxController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('moduluxes', ModuluxController::class)->names('modulux');
});

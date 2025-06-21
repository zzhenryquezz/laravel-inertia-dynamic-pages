<?php

use Illuminate\Support\Facades\Route;
use Modules\Modulux\Http\Controllers\ModuluxController;

Route::middleware(['web'])->group(function () {
    Route::resource('moduluxes', ModuluxController::class)->names('modulux');
});

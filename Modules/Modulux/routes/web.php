<?php

use Illuminate\Support\Facades\Route;
use Modules\Modulux\Http\Controllers\ModuluxController;

Route::resource('moduluxes', ModuluxController::class)->names('modulux');
// Route::middleware(['auth', 'verified'])->group(function () {
// });

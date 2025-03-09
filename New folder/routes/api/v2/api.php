<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pandaProductsController;

Route::group(['namespace' => 'Api\V2'], function () {
    Route::post('ls-lib-update', 'LsLibController@lib_update');
    Route::post('ramez', [pandaProductsController::class, 'fetchRamezProducts']);
});

<?php

use \EscolaLms\Files\Http\Controllers\FileApiController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function () {
    Route::group(['prefix' => 'file'], function () {
        Route::get('list', [FileApiController::class, 'list']);
        Route::post('upload', [FileApiController::class, 'upload']);
        Route::post('move', [FileApiController::class, 'move']);
        Route::delete('delete', [FileApiController::class, 'delete']);
    });
});

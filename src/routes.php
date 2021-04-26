<?php

use \EscolaLms\Files\Http\Controllers\FileApiController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function () {
    Route::group(['prefix' => 'file'], function () {
        Route::post('list', [FileApiController::class, 'list']);
        Route::post('upload', [FileApiController::class, 'upload']);
        Route::post('move', [FileApiController::class, 'move']);
        Route::post('delete', [FileApiController::class, 'delete']);
    });
});

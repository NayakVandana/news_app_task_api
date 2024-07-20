<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;

 // images
 Route::controller(NewsController::class)->group(function () {
    Route::post('get-article-list', 'index');   
    Route::post('search', 'search');   
});
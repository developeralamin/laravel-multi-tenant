<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\Tenant\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

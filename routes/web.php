<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AllImageController;


Route::get('/', function () {
    return view('welcome');
});

Route::resource('posts', PostController::class);

Route::resource('products', ProductController::class);

// all image
Route::get('/image-crud', [AllImageController::class, 'index'])->name('image.index');
Route::post('/image-crud', [AllImageController::class, 'store'])->name('image.store');
Route::delete('/image-crud/{id}', [AllImageController::class, 'destroy'])->name('image.destroy');

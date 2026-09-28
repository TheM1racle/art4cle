<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'home']);

Route::get('/category', [PageController::class, 'category']);

Route::get('/journalist', [PageController::class, 'journalist']);

Route::get('/admin', [PageController::class, 'admin']);

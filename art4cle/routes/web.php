<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;


Route::get('/', [PageController::class, 'home']);
Route::get('/category', [PageController::class, 'category']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


Route::get('/journalist', [PageController::class, 'journalist'])->middleware('role:Journalist,Admin');
Route::post('/journalist', [PageController::class, 'store'])->middleware('role:Journalist,Admin');


Route::get('/admin', [PageController::class, 'admin'])->middleware('role:Admin');
Route::get('/admin/edit/{text}', [PageController::class, 'edit'])->middleware('role:Admin');
Route::post('/admin/update/{text}', [PageController::class, 'update'])->middleware('role:Admin');
Route::post('/admin/block/{text}', [PageController::class, 'block'])->middleware('role:Admin');
Route::post('/admin/delete/{text}', [PageController::class, 'destroy'])->middleware('role:Admin');
Route::post('/admin/role', [PageController::class, 'changeRole'])->middleware('role:Admin');

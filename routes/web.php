<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TestC;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/check-balance', [TestC::class, 'checkBalance']);
Route::get('/withdraw-detail', [TestC::class, 'withdrawDetail']);
Route::get('/', [TestC::class, 'withdraw']);




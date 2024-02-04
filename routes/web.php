<?php

use Elyerr\Passport\Connect\Controllers\CodeController;
use Illuminate\Support\Facades\Route;

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

Route::get('/login', [CodeController::class, 'login'])->name('login');
Route::get('/redirect', [CodeController::class, 'redirect'])->name('redirect');
Route::get('/callback', [CodeController::class, 'callback'])->name('callback');

Route::get('/', function () {
    return view('welcome');
});

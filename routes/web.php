<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Main;

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


// ******** LOGIN ROUTES *********
Route::get('/login', [Main::class, 'login'])->name('login');
Route::post('/login_submit', [Main::class, 'login_submit'])->name('login_submit');

// ******** MIDDLEWARE *********
Route::middleware('CheckLogin')->group(function () {

    Route::get('/', [Main::class, 'index'])->name('index');
    // ******** MAIN PAGE *********
    Route::get('/main', [Main::class, 'main'])->name('main');
});

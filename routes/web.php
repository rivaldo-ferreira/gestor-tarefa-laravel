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

Route::get('/', function () {

    try {
        DB::connection()->getPdo();
        echo "Conexão efetuada com sucesso ao BD: " . DB::connection()->getDataBaseName();
    } catch (\Exception $e) {
        die('Não foi possível conectar à base de dados! ' . $e->getMessage());
    }
});

Route::get('/main', [Main::class, 'index']);

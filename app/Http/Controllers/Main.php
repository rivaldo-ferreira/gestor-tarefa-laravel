<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Main extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Teste com laravel',
            'descricao' => 'Carregar informações!',
        ];
        return view('main', $data);
    }
}

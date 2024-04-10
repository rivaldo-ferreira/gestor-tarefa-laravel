<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Main extends Controller
{
    public function index()
    {
        echo 'Gestor de Tarefas';
    }

    public function login()
    {
        $data = [
            'title' => 'Login',
        ];

        return view('login_frm', $data);
    }

    public function login_submit()
    {
        echo 'Dados do formulário de login';
    }

    //main page
    public function main()
    {
        $data = [
            'title' => 'Main',
        ];

        return view('main', $data);
    }
}

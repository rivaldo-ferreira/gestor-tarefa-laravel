<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Main extends Controller
{
    /* ------------------ MAIN PAGE ------------------ */
    public function index()
    {
        $data = [
            'title' => 'Gestor de Tarefas'
        ];

        return view('main', $data);
    }

    /* ------------------ LOGIN ------------------ */
    public function login()
    {
        $data = [
            'title' => 'Login',
        ];

        return view('login_frm', $data);
    }

    public function login_submit()
    {
        //...
    }

    /* ------------------ LOGOUT ------------------ */
    public function logout()
    {
        session()->forget('username');
        return redirect()->route('login');
    }
}

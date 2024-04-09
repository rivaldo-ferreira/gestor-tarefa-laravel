<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function users()
    {
        //********** get users with raw sql ***************
        // $users = DB::select('SELECT * FROM users');
        // dd($users);

        // ************** with query builder ****************
        // $users = DB::table('users')->get();
        // dd($users);

        // ************** with query builder toarray ****************
        // $users = DB::table('users')->get()->toArray();
        // dd($users);

        // ************** using eloquent ORM ****************
        $model = new UserModel();
        $users = $model->all();
        // dd($users);

        foreach ($users as $user) {
            echo $user->username . '<br>';
        }
    }
}

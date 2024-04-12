<?php

namespace App\Http\Controllers;

use App\Models\TaskModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Main extends Controller
{
    /* ------------------ MAIN PAGE ------------------ */
    public function index()
    {
        $data = [
            'title' => 'Gestor de Tarefas',
            'tasks' => $this->_get_tasks(),
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

    public function login_submit(Request $request)
    {
        //form validation
        request()->validate([
            'text_username' => 'required|min:3',
            'text_password' => 'required|min:3',
        ], [
            'text_username.required' => 'Campo obrigatório',
            'text_username.min' => 'Três caracteres no mínimo!',
            'text_password.required' => 'Campo obrigatório',
            'text_password.min' => 'Três caracteres no mínimo!',
        ]);

        //lógica do login
        $username = $request->input('text_username');
        $password = $request->input('text_password');

        //usuario existe?
        $model = new UserModel();
        $user = $model->where('username', '=', $username)->whereNull('deleted_at')->first();

        if ($user) {
            if (password_verify($password, $user->password)) {
                $session_data = [
                    'id' => $user->id,
                    'username' => $user->username,
                ];
                session()->put($session_data);
                return redirect()->route('index');
            }
        }
        // login invalido
        return redirect()->route('login')->withInput()->with('login_error', 'Login inválido!');
    }

    /* ------------------ LOGOUT ------------------ */
    public function logout()
    {
        session()->forget('username');
        return redirect()->route('login');
    }

    /* ------------------ NEW TASK ------------------ */
    public function new_task()
    {
        $data = [
            'title' => 'Nova Tarefa',
        ];

        return view('new_task_frm', $data);
    }

    public function new_task_submit()
    {
        echo 'Guardar nova tarefa';
    }

    /* ------------------ PRIVATE METHODS ------------------ */
    private function _get_tasks()
    {
        $model = new TaskModel();
        return $model->where('id_user', '=', session()->get('id'))
            ->whereNull('deleted_at')
            ->get();
    }
}

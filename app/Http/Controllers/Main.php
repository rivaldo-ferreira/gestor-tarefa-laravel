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

    public function new_task_submit(Request $request)
    {
        //form validation
        request()->validate([
            'text_task_name' => 'required|min:3|max:200',
            'text_task_description' => 'required|min:3|max:1000',
        ], [
            'text_task_name.required' => 'Campo obrigatório',
            'text_task_name.min' => ':min caracteres no mínimo!',
            'text_task_name.max' => ':max caracteres no máximo!',

            'text_task_description.required' => 'Campo obrigatório',
            'text_task_description.min' => ':min caracteres no mínimo!',
            'text_task_description.max' => ':max caracteres no máximo!',
        ]);

        //get from data
        $task_name = $request->input('text_task_name');
        $task_description = $request->input('text_task_name');
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

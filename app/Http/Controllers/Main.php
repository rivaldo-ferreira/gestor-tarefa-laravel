<?php

namespace App\Http\Controllers;

use App\Models\TaskModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class Main extends Controller
{
    /* ------------------ MAIN PAGE ------------------ */
    public function index()
    {
        $data = [
            'title' => 'Gestor de Tarefas',
            'datatables' => true,
            // 'tasks' => $this->_get_tasks(),
        ];

        //checkif there is a search
        if (session('search')) {
            $data['search'] = session('search');
            $data['tasks'] = $this->_get_tasks(session('tasks'));

            //limpar a sessao
            session()->forget('search');
            session()->forget('tasks');
        } else {
            $model = new TaskModel();
            $tasks = $model->where('id_user', '=', session('id'))
                ->whereNull('deleted_at')
                ->get();

            $data['tasks'] = $this->_get_tasks($tasks);
        }


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

        //existe alguma tarefa com o mesmo nome?
        $model = new TaskModel();
        $task = $model->where('id_user', '=', session('id'))
            ->where('task_name', '=', $task_name)
            ->whereNull('deleted_at')
            ->first();

        if ($task) {
            return redirect()->route('new_task')->with('task_error', 'Já existe uma tarefa com o mesmo nome!');
        }

        //inserir nova tarefa na tabela
        $model->id_user = session('id');
        $model->task_name = $task_name;
        $model->task_description = $task_description;
        $model->task_status = 'new';
        $model->created_at = date('Y-m-d H:i:s');
        $model->save();

        return redirect()->route('index');
    }

    /* ------------------ EDIT TASK ------------------ */

    public function edit_task($id)
    {

        try {

            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {

            return redirect()->route('index');
        }

        //buscar dados da tarefa
        $model = new TaskModel();
        $task = $model->where('id', '=', $id)
            ->where('id_user', '=', session('id')) //opcional
            ->whereNull('deleted_at') //opcional
            ->first();

        //checar se a tarefa existe
        if (empty($task)) {
            return redirect()->route('index');
        }

        $data = [
            'title' => 'Editar Tarefa',
            'task' => $task,
        ];

        return view('edit_task_frm', $data);
    }

    public function edit_task_submit(Request $request)
    {
        //form validation
        request()->validate([
            'text_task_name' => 'required|min:3|max:200',
            'text_task_description' => 'required|min:3|max:1000',
            'text_task_status' => 'required',
        ], [
            'text_task_name.required' => 'Campo obrigatório',
            'text_task_name.min' => ':min caracteres no mínimo!',
            'text_task_name.max' => ':max caracteres no máximo!',

            'text_task_description.required' => 'Campo obrigatório',
            'text_task_description.min' => ':min caracteres no mínimo!',
            'text_task_description.max' => ':max caracteres no máximo!',

            'text_task_status.required' => 'Campo obrigatório'
        ]);

        //get form  data
        $id_task = null;
        try {
            $id_task = Crypt::decrypt($request->input('task_id'));
        } catch (\Exception $e) {
            return redirect()->route('index');
        }


        $task_name = $request->input('text_task_name');
        $task_description = $request->input('text_task_description');
        $task_status = $request->input('text_task_status');

        //existe alguma tarefa com o mesmo nome
        $model = new TaskModel();
        $task = $model->where('id_user', '=', session('id'))
            ->where('task_name', '=', $task_name)
            ->where('id', '!=', $id_task)
            ->whereNull('deleted_at')
            ->first();

        if ($task) {
            return redirect()->route('edit_task', ['id' => Crypt::encrypt($id_task)])
                ->with('task_error', 'Já existe uma tarefa com esse nome!');
        }

        // update task 
        $model->where('id', '=', $id_task)
            ->update([
                'task_name' => $task_name,
                'task_description' => $task_description,
                'task_status' => $task_status,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return redirect()->route('index');
    }
    /* ------------------ DELETE TASK ------------------ */

    public function delete_task($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return redirect()->route('index');
        }

        //get task data
        $model = new TaskModel();
        $task = $model->where('id', '=', $id)->first();
        if (!$task) {
            return redirect()->route('index');
        }

        $data = [
            'title' => 'Excluir Tarefa',
            'task' => $task,
        ];

        return view('delete_task', $data);
    }

    public function delete_task_confirm($id)
    {
        $id_task = null;

        try {
            $id_task = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return redirect()->route('index');
        }

        //delete task
        $model = new TaskModel();
        $model->where('id', '=', $id_task)
            ->update([
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);
        return redirect()->route('index');
    }

    /* ------------------ SEARCH & SORT ------------------ */
    public function search_submit(Request $request)
    {
        //get from form data
        $search = $request->input('text_search');

        //get tasks
        $model = new TaskModel();
        if ($search == '') {
            $tasks = $model->where('id_user', '=', session('id'))
                ->whereNull('deleted_at')
                ->get();
        } else {
            $tasks = $model->where('id_user', '=', session('id'))
                ->where(function ($query) use ($search) {
                    $query->where('task_name', 'like', '%' . $search . '%')
                        ->orWhere('task_description', 'like', '%' . $search . '%');
                })
                ->whereNull('deleted_at')
                ->get();
        }

        session()->put('tasks', $tasks);
        session()->put('search', $search);

        return redirect()->route('index');
    }

    public function sort($status)
    {
        echo 'Sort';
    }


    /* ------------------ PRIVATE METHODS ------------------ */
    private function _get_tasks($tasks)
    {
        // $model = new TaskModel();
        // $tasks =  $model->where('id_user', '=', session('id'))
        //     ->whereNull('deleted_at')
        //     ->get();
        // ===================================
        $collection = [];

        foreach ($tasks as $task) {

            $link_edit = '<a href="' . route('edit_task', ['id' => Crypt::encrypt($task->id)]) . '" class="btn btn-secondary m-1"><i class="bi bi-pencil-square"></i></a>';
            $link_delete = '<a href="' . route('delete_task', ['id' => Crypt::encrypt($task->id)]) . '" class="btn btn-secondary m-1"><i class="bi bi-trash"></i></a>';

            $collection[] = [
                'task_name' => $task->task_name,
                'task_status' => $this->_status_name($task->task_status),
                'task_actions' => $link_edit . $link_delete,
            ];
        }

        return $collection;
    }

    private function _status_name($status)
    {
        $status_collection = [
            'new' => 'Nova',
            'in_progress' => 'Em andamento',
            'cancelled' => 'Cancelada',
            'completed' => 'Concluída',
        ];

        if (key_exists($status, $status_collection)) {
            return $status_collection[$status];
        } else {
            return 'Desconhecido';
        }
    }
}

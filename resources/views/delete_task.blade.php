@extends('template/main_layout')

@section('content')

<div class="container">
    <div class="row mt-5">
        <div class="col">
            <h4>Excluir Tarefa</h4>
            <hr>
            <h4 class="text-info">{{ $task->task_name }}</h4>
            <p class="opacity-50">{{ $task->task_description }}</p>
            <p class="my-5 text-center">Deseja excluir esta tarefa?</p>
            <div class="my-4 text-center">
                <a href="{{ route('index') }}" class="btn btn-dark px-5 m-1"><i class="bi bi-x-circle me-2">Cancelar</i></a>
                <a href="{{ route('delete_task_confirm',['id'=>Crypt::encrypt($task->id)]) }}" class="btn btn-danger px-5 m-1"><i class="bi bi-trash me-2">Excluir</i></a>
            </div>
        </div>
    </div>
</div>
    
@endsection

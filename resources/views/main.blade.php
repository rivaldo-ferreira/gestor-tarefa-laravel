@extends('template/main_layout')

@section('content')

<div class="container">
    <div class="row">
        <div class="col">
            <h4 class="fw-bold">Tarefas</h4>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th class="w-50">Tarefa</th>
                        <th class="w-25 text-center">Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <p class="text-center opacity-50 my-3">Nenhuma tarefa foi registrada!</p>
        </div>
    </div>
</div>
    
@endsection

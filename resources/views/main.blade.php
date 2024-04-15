@extends('template/main_layout')

@section('content')

<div class="container">
    <div class="row">
        <div class="col">
            <div class="row align-itens-center">
                <div class="col">
                    <h4 class="fw-bold">Tarefas</h4>
                </div>
                <div class="col text-end">
                    <a href="{{ route('new_task') }}" class="btn btn-primary my-3"><i class="bi bi-plus-square me-2"></i>Nova Tarefa</a>
                </div>
            </div>

            @if(count($tasks) != 0)
                <table class="table table-striped table-bordered" id="table_tasks" width="100%">
                    <thead class="table-dark">
                        <tr>
                            <th class="w-75">Tarefa</th>
                            <th class="text-center">Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach($tasks as $task)
                            <tr>
                                <td>{{ $task->task_name }}</td>
                                <td class="text-center">{{ $task->task_status }}</td>
                                <td class="text-center">[actions]</td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            @else
                <p class="text-center opacity-50 my-5">Nenhuma tarefa foi registrada!</p>
            @endif
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#table_tasks').DataTable({
            data: @json($tasks),
            columns: [
                { data: 'task_name'},
                { data: 'task_status', className: 'text-center'},
                { data: 'task_actions', className: 'text-center'},
            ]
        });
    });
</script>
    
@endsection

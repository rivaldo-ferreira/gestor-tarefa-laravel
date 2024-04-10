@extends('template/login_layout')

@section('content')

<div class="login-wrapper">
    <div class="login-box">
        <h3 class="text-center fw-bold">Login</h3>

        <hr>
        <form action="{{ route('login_submit') }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="text_username" class="form-label">Usuário</label>
                <input type="text" name="text_username" id="text_username" class="form-control" placeholder="Usuário" required>
            </div>
            <div class="mb-3">
                <label for="text_password" class="form-label">Usuário</label>
                <input type="password" name="text_password" id="text_password" class="form-control" placeholder="Usuário" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-dark w-100">Entrar</button>
            </div>
        </form>
    </div>
</div>



@endsection
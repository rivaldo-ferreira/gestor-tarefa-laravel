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
                <input type="text" name="text_username" id="text_username" class="form-control" placeholder="Usuário" required value="{{ old('text_username') }}">
                {{-- AVISO DE ERRO --}}
                @error('text_username')
                    <div class="text-warning">{{ $errors->get('text_username')[0] }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="text_password" class="form-label">Senha</label>
                <input type="password" name="text_password" id="text_password" class="form-control" placeholder="Senha" required value="{{ old('text_password') }}">
                {{-- AVISO DE ERRO --}}
                @error('text_password')
                    <div class="text-warning">{{ $errors->get('text_password')[0] }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-dark w-100">Entrar</button>
            </div>
            @if(session()->has('login_error'))
                <div class="alert alert-danger p-1 text-center">
                    {{ session()->get('login_error') }}
                </div>
            @endif
        </form>
         
    </div>
</div>
@endsection
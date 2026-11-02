@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <h1>Вход в систему</h1>

    <form action="/login" method="POST" class="article-form">
        @csrf

        <div class="field">
            <label for="login">Логин</label>
            <input type="text" id="login" name="login" value="{{ old('login') }}" required>
            @error('login')
                <p style="color: #d0021b; font-size: 13px; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn-publish">Войти</button>
    </form>
@endsection

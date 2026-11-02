@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <h1>Регистрация журналиста</h1>

    <form action="/register" method="POST" class="article-form">
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
            @error('password')
                <p style="color: #d0021b; font-size: 13px; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Подтвердите пароль</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn-publish">Создать аккаунт</button>
    </form>
@endsection

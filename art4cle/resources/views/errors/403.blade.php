@extends('layouts.app')

@section('title', '403 — Доступ запрещён')

@section('content')
    <div class="error-page">
        <h1>403</h1>
        <h2>Доступ в этот раздел запрещён</h2>


        <p class="error-message">{{ $exception->getMessage() }}</p>

        <img src="{{ asset('images/403.jpg') }}" alt="Доступ запрещён" class="error-img">

        <div>
            <a href="/" class="btn-publish" style="display: inline-block; text-decoration: none;">
                Вернуться на главную
            </a>
        </div>
    </div>
@endsection

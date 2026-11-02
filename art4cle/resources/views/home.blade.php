@extends('layouts.app')
@section('title', 'Главная')
@section('content')
    <h1>Лента новостей</h1>
    @foreach ($texts as $text)
        <article class="card">
            <h2>{{ $text->heading }}</h2>
            <p class="card-p-category">{{ $text->category->name }}</p>

            <p style="font-size: 13px; color: #666; margin-bottom: 10px;">
                Автор: {{ $text->author ? $text->author->login : 'Неизвестный' }}
            </p>

            <p>{{ $text->text }}</p>
            <p class="card-date">{{ $text->created_at->format('d.m.Y') }}</p>
        </article>
    @endforeach
@endsection

@extends('layouts.app')
@section('title', 'Категория')
@section('content')
    <h1>Категория: Хронология</h1>
    @foreach ($texts as $text)
        <article class="card">
            <h2>{{ $text->heading }}</h2>
            <p class="card-p-category">{{ $text->category->name }}</p>
            <p>{{ $text->text }}</p>
        </article>
    @endforeach
@endsection

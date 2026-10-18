@extends('layouts.app')
@section('title', 'Категория')
@section('content')
    @foreach ($articles as $article)
        <article class="card">
            <h2>{{ $article->title }}</h2>
            <p class="card-p-category">{{ $article->category->name }}</p>
            <p>{{ $article->text }}</p>
        </article>
    @endforeach
@endsection

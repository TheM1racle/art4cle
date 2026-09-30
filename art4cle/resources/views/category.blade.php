@extends('layouts.app')
@section('title', 'Категория')
@section('content')
    @foreach ($articles as $article)
        @if ($article['category'] === 'Хронология')
            <article class="card">
                <h2>{{$article['title']}}</h2>
                <p class="card-p-category">{{$article['category']}}</p>
                <p>{{$article['text']}}</p>
            </article>
        @endif
    @endforeach
@endsection

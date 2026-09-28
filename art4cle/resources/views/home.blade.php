@extends('layouts.app')
@section('title', 'Главная')

@section('content')
    <h1>Главная страница</h1>
    @foreach ($articles as $article)
        <article class="card">
            <h2>{{$article['title']}}</h2>
            <p class="card-p-category">{{$article['category']}}</p>
            <p>{{$article['text']}}</p>
        </article>
    @endforeach
@endsection

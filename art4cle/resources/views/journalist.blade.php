@extends('layouts.app')

@section('title', 'Журналист')

@section('content')
    <h1>Кабинет журналиста</h1>

    <form action="/journalist" method="POST" class="article-form">
    @csrf

    <div class="field">
    <label for="heading">Заголовок статьи</label>
    <input type="text" id="heading" name="heading" value="{{ old('heading') }}" required>
    @error('heading') <p style="color:red">{{ $message }}</p> @enderror
    </div>

    <div class="field">
        <label for="category">Категория</label>

        <select id="category" name="category_id" required>
            <option value="">— Выберите категорию —</option>

            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="field">
        <label for="text">Текст статьи</label>
        <textarea id="text" name="text" rows="10" required></textarea>
    </div>

    <button type="submit" class="btn-publish">Опубликовать</button>
</form>
@endsection

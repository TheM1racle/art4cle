@extends('layouts.app')

@section('title', 'Редактирование статьи')

@section('content')
    <h1>Редактирование: {{ $text->heading }}</h1>

    <form action="/admin/update/{{ $text->id }}" method="POST" class="article-form">
        @csrf

        <div class="field">
            <label for="heading">Заголовок</label>
            <input type="text" id="heading" name="heading" value="{{ old('heading', $text->heading) }}" required>
            @error('heading') <p style="color: #d0021b; font-size: 13px;">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="category">Категория</label>
            <select id="category" name="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected($category->id === $text->category_id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="field">
            <label for="text">Текст статьи</label>
            <textarea id="text" name="text" rows="10" required>{{ old('text', $text->text) }}</textarea>
        </div>

        <button type="submit" class="btn-publish">Сохранить</button>
        <a href="/admin" style="margin-left: 12px; color: #666;">Отмена</a>
    </form>
@endsection

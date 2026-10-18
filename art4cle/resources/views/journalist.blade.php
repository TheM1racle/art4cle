@extends('layouts.app')

@section('title', 'Журналист')

@section('content')
    <h1>Кабинет журналиста</h1>

    <form action="#" method="POST" class="article-form">
        <div class="field">
            <label for="title">Заголовок статьи</label>
            <input type="text" id="title" name="title" placeholder="Например: Песчаная Язва отступает?" required>
        </div>

        <div class="field">
            <label for="category">Категория</label>
            <select id="category" name="category" required>
                <option value="">— Выберите категорию —</option>
                <option value="zakon">Закон</option>
                <option value="health">Здравоохранение</option>
                <option value="tragik">Трагик</option>
                <option value="hronology">Хронология</option>
            </select>
        </div>

        <div class="field">
            <label for="text">Текст статьи</label>
            <textarea id="text" name="text" rows="10" placeholder="Пишите правду. Только правду." required></textarea>
        </div>

        <button type="submit" class="btn-publish">Опубликовать</button>
    </form>
@endsection

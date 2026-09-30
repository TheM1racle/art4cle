@extends('layouts.app')
@section('title', 'Журналист')
@section('content')
    <h1>журналист</h1>
    <section>
        <div>
            <input type="text" name="title">
            <select name="category">
                <option value="">Выбирай</option>
                <option value="zakon">Закон</option>
                <option value="health">Здравоохранение</option>
                <option value="tragik">Трагик</option>
                <option value="hronology">Хронология</option>
            </select>
            <textarea name="text" rows="10">
            </textarea>
            <button type="submit">
            </button>
        </div>
@endsection

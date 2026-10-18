@extends('layouts.app')

@section('title', 'Админ-панель')

@section('content')
    <h1>Панель администратора</h1>

    <section class="admin-section">
        <h2>Управление статьями</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Заголовок</th>
                    <th>Категория</th>
                    <th>Автор</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Смерть — лучший лекарь</td>
                    <td>Здравоохранение</td>
                    <td>Бакалавр</td>
                    <td>
                        <button class="btn-edit">Ред.</button>
                        <button class="btn-block">Блок</button>
                        <button class="btn-delete">Удал.</button>
                    </td>
                </tr>

            </tbody>
        </table>
    </section>

    <section class="admin-section">
        <h2>Изменение ролей пользователей</h2>
        <form action="#" method="POST" class="role-form">
            <div class="field">
                <label for="username">Логин пользователя</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="field">
                <label for="role">Новая роль</label>
                <select id="role" name="role" required>
                    <option value="reader">Читатель</option>
                    <option value="journalist">Журналист</option>
                    <option value="admin">Администратор</option>
                </select>
            </div>
            <button type="submit" class="btn-publish">Изменить роль</button>
        </form>
    </section>
@endsection

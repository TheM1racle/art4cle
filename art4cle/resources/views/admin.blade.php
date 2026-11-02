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
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($texts as $text)
                    <tr>
                        <td>{{ $text->heading }}</td>
                        <td>{{ $text->category->name }}</td>
                        <td>{{ $text->author?->login ?? '—' }}</td>
                        <td>
                            @if ($text->is_blocked)
                                <span style="color: #d0021b; font-weight: bold;">Заблокирована</span>
                            @else
                                <span style="color: #2e7d32;">Опубликована</span>
                            @endif
                        </td>
                        <td>
                            <a href="/admin/edit/{{ $text->id }}" class="btn-edit" style="text-decoration: none;">Ред.</a>

                            <form action="/admin/block/{{ $text->id }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-block">
                                    {{ $text->is_blocked ? 'Разблок.' : 'Блок.' }}
                                </button>
                            </form>

                            <form action="/admin/delete/{{ $text->id }}" method="POST" style="display: inline;"
                                  onsubmit="return confirm('Удалить статью навсегда?');">
                                @csrf
                                <button type="submit" class="btn-delete">Удал.</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <section class="admin-section">
        <h2>Изменение ролей пользователей</h2>
        <form action="/admin/role" method="POST" class="role-form">
            @csrf

            <div class="field">
                <label for="user_id">Пользователь</label>
                <select id="user_id" name="user_id" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->login }} (сейчас: {{ $user->role?->name ?? 'без роли' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="role_id">Новая роль</label>
                <select id="role_id" name="role_id" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-publish">Изменить роль</button>
        </form>
    </section>
@endsection

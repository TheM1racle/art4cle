<header>
    <a href="/" class="logo">Вестник Города</a>

    <nav>
        <a href="/">Главная</a>
        <a href="/category">Категории</a>

        @auth

            @if (in_array(auth()->user()->role?->name, ['Journalist', 'Admin']))
                <a href="/journalist">Журналист</a>
            @endif

            {{-- только для Admin --}}
            @if (auth()->user()->role?->name === 'Admin')
                <a href="/admin">Админ</a>
            @endif
        @endauth
    </nav>

    <div style="margin-left: auto; display: flex; align-items: center; gap: 15px;">
        @guest
            <a href="/login">Вход</a>
            <a href="/register">Регистрация</a>
        @else
            <span style="color: #e8e0d0;">Привет, {{ auth()->user()->login }}</span>
            <form action="/logout" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: #d4a017; cursor: pointer; font-size: inherit; padding: 0;">Выйти</button>
            </form>
        @endguest
    </div>
</header>

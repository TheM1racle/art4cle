<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css'])
</head>

<x-header />
<main>
    @yield('content')
</main>
<x-footer />

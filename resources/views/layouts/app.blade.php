<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css'])
    @stack('vite')
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">
    <header class="bg-white border-b border-gray-200">
        <nav class="max-w-4xl mx-auto px-4 py-3 flex gap-4 text-sm">
            <a href="/" class="text-gray-600 hover:text-gray-900">Home</a>
            <a href="{{ route('demo.index') }}" class="text-gray-600 hover:text-gray-900">Demo</a>
            @auth
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Login</a>
            @endauth
        </nav>
    </header>
    <main class="max-w-4xl mx-auto px-4 py-8">
        @yield('content')
    </main>
</body>
</html>

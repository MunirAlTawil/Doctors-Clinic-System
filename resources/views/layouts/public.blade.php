<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title')@else{{ config('app.name') }}@endif</title>
    @include('partials.head-icons')
    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="ui-public-body flex min-h-screen flex-col">
    @include('partials.ambient-bg')

    @hasSection('header')
        @yield('header')
    @else
        @include('partials.public-header')
    @endif

    <div class="relative z-10 flex-1">
        @yield('content')
    </div>

    @hasSection('footer')
        @yield('footer')
    @else
        @include('partials.public-footer')
    @endif
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('images/favicon.png')}}">
    <link rel="icon" type="image/x-icon" href="{{asset('images/favicon.ico')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>@yield('title') {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="#">
    <meta name="keywords" content="#">
    @livewireStyles
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/site.css'])
    @vite(['resources/js/site.js'])
</head>

<body class="overflow-x-hidden dark:bg-gray-800">
    <main>
        @yield('content')
    </main>
    @include('footer.footer')
    @livewireScripts
</body>
</html>

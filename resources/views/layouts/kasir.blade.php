<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title.' - '.config('app.name') : 'Kasir - '.config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <header class="flex h-14 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <a href="{{ route('pos.index') }}" class="text-base font-semibold text-gray-800">Kasir</a>
                    <span class="hidden text-sm text-gray-500 sm:inline">{{ config('app.name') }}</span>
                </div>

                <div class="flex items-center gap-4 text-sm">
                    <div class="hidden text-right sm:block">
                        <p class="font-medium text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">Kasir</p>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-gray-900">Profil</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-900">Keluar</button>
                    </form>
                </div>
            </header>

            <main class="p-4 sm:p-6">
                {{ $slot }}
            </main>
        </div>

        <x-toast />
    </body>
</html>
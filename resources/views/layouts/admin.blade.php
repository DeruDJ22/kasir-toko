@php
    $menu = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'active' => 'dashboard'],
        ['label' => 'Kategori', 'route' => 'kategori.index', 'active' => 'kategori.*'],
        ['label' => 'Barang', 'route' => 'barang.index', 'active' => 'barang.*'],
        ['label' => 'Karyawan', 'route' => 'karyawan.index', 'active' => 'karyawan.*'],
        ['label' => 'Penjualan', 'route' => 'penjualan.index', 'active' => 'penjualan.*'],
        ['label' => 'Laporan', 'route' => 'laporan.index', 'active' => 'laporan.*'],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-gray-100">
            <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-gray-900/50 lg:hidden"></div>

            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 flex w-64 flex-col border-r border-gray-200 bg-white transition-transform duration-200 lg:translate-x-0">
                <div class="flex h-16 shrink-0 items-center border-b border-gray-200 px-6">
                    <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-gray-800">{{ config('app.name') }}</a>
                </div>

                <nav class="flex-1 space-y-1 overflow-y-auto p-4">
                    @foreach ($menu as $item)
                        @if (Route::has($item['route']))
                            <a href="{{ route($item['route']) }}" class="block rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs($item['active']) ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                {{ $item['label'] }}
                            </a>
                        @endif
                    @endforeach
                </nav>
            </aside>

            <div class="lg:pl-64">
                <header class="flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6">
                    <div class="flex items-center gap-3">
                        <button type="button" @click="sidebarOpen = true" class="rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 lg:hidden" aria-label="Buka menu">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <h1 class="text-lg font-semibold text-gray-800">{{ $header ?? '' }}</h1>
                    </div>

                    <div class="flex items-center gap-4 text-sm">
                        <div class="hidden text-right sm:block">
                            <p class="font-medium text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->role === 'admin' ? 'Admin' : 'Kasir' }}</p>
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
        </div>

        <x-toast />
    </body>
</html>

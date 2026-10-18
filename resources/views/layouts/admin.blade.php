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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <script>
            (function() {
                var t = localStorage.getItem('theme');
                if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>
<script>window.Swal = window.Swal || {};</script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen">
            <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-slate-900/60 backdrop-blur-sm lg:hidden"></div>

            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 flex w-64 flex-col border-r border-slate-200 bg-white transition-transform duration-200 lg:translate-x-0 dark:border-slate-800 dark:bg-slate-900">
                <div class="flex h-16 shrink-0 items-center border-b border-slate-200 px-6 dark:border-slate-800">
                    <a href="{{ route('dashboard') }}" class="text-base font-bold tracking-tight text-slate-800 dark:text-slate-100">{{ config('app.name') }}</a>
                </div>

                <nav class="flex-1 space-y-1 overflow-y-auto p-4">
                    @foreach ($menu as $item)
                        @if (Route::has($item['route']))
                            <a href="{{ route($item['route']) }}" class="nav-item {{ request()->routeIs($item['active']) ? 'nav-item-active' : '' }}">
                                {{ $item['label'] }}
                            </a>
                        @endif
                    @endforeach
                </nav>
            </aside>

            <div class="lg:pl-64">
                <header class="sticky top-0 z-10 flex h-16 items-center justify-between border-b border-slate-200 bg-white/80 px-4 backdrop-blur-md sm:px-6 dark:border-slate-800 dark:bg-slate-900/80">
                    <div class="flex items-center gap-3">
                        <button type="button" @click="sidebarOpen = true" class="rounded-lg p-2 text-slate-500 transition-colors hover:bg-slate-100 lg:hidden dark:text-slate-400 dark:hover:bg-slate-800" aria-label="Buka menu">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <h1 class="page-title">{{ $header ?? '' }}</h1>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-4 text-sm">
                        <button
                            x-data="{ dark: document.documentElement.classList.contains('dark') }"
                            @click="dark = !dark; document.documentElement.classList.toggle('dark', dark); localStorage.setItem('theme', dark ? 'dark' : 'light')"
                            class="rounded-lg p-2 text-slate-500 transition-colors hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800"
                            aria-label="Ganti tema"
                        >
                            <svg x-show="!dark" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg x-show="dark" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>

                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 rounded-lg p-1 text-slate-500 transition-colors hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800">
                                <div class="h-8 w-8 rounded-full bg-slate-300 dark:bg-slate-600 flex items-center justify-center">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14c-3.31 0-6-2.69-6-6 0-.2.04-.4.09-.59C8.06 14.64 9.8 16 12 16c2.2 0 4-1.36 4.89-3.29.05.19.09.39.09.59 0 3.31-2.69 6-6 6z"/>
                                    </svg>
                                </div>
                            </button>
                            <div x-show="open" x-cloak @click.away="open = false" class="absolute right-0 mt-2 w-48 rounded-md border border-slate-200 bg-white py-1 shadow-lg dark:border-slate-700 dark:bg-slate-800">
                                <div class="px-4 py-2 text-sm font-medium text-slate-800 dark:text-slate-100">{{ Auth::user()->name }}</div>
                                <div class="px-4 py-1 text-xs text-slate-500 dark:text-slate-400">{{ Auth::user()->role === 'admin' ? 'Admin' : 'Kasir' }}</div>
                                <hr class="my-1 border-slate-200 dark:border-slate-700" />
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Profil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Keluar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <x-toast />
    <script>
document.addEventListener('DOMContentLoaded', function() {
    @if (session('success'))
        Swal.fire({title: 'Berhasil', text: '{{ session("success") }}', icon: 'success', timer: 3000, showConfirmButton: false});
    @endif
    @if (session('error'))
        Swal.fire({title: 'Gagal', text: '{{ session("error") }}', icon: 'error', timer: 5000, showConfirmButton: false});
    @endif
});
</script>
</body>
</html>

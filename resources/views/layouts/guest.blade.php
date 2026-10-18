<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
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
    <body class="h-full font-sans text-slate-800 antialiased dark:text-slate-200">
        <div class="flex min-h-full flex-col items-center justify-center bg-slate-100 py-10 px-4 dark:bg-slate-950">
            <div>
                <a href="/" class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                    {{ config('app.name') }}
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-soft rounded-xl border border-slate-200 dark:border-slate-800 dark:bg-slate-900">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-slate-500 dark:text-slate-500">{{ config('app.name') }} &copy; {{ date('Y') }}</p>
        </div>
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

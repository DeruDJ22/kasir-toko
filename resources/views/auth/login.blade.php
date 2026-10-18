<x-guest-layout :title="'Masuk'">
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h1 class="mb-1 text-lg font-semibold tracking-tight text-slate-900 dark:text-slate-100">Masuk ke akun kamu</h1>
    <p class="mb-6 text-sm text-slate-500 dark:text-slate-400">Gunakan akun kasir atau admin yang sudah terdaftar.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@toko.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-offset-slate-900" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-slate-400">Ingat saya</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="ms-3 w-full justify-center">
                Masuk
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

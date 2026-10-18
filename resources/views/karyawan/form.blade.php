<x-admin-layout title="{{ $mode === 'create' ? 'Tambah Karyawan' : 'Edit Karyawan' }}">
    <x-slot name="header">
        <a href="{{ route('karyawan.index') }}" class="text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-200">Karyawan</a>
        <span class="px-1 text-gray-400 dark:text-slate-600">/</span>
        <span>{{ $mode === 'create' ? 'Tambah' : 'Edit' }}</span>
    </x-slot>

    <div class="max-w-2xl rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6">
        <form method="POST" action="{{ $mode === 'create' ? route('karyawan.store') : route('karyawan.update', $karyawan) }}" class="space-y-4">
            @csrf
            @if ($mode === 'edit')
                @method('PUT')
            @endif

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('name', $karyawan->name) }}" class="mt-1 block w-full rounded-md border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" required maxlength="100">
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $karyawan->email) }}" class="mt-1 block w-full rounded-md border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nip" class="block text-sm font-medium text-gray-700 dark:text-slate-300">NIP</label>
                <input type="text" name="nip" id="nip" value="{{ old('nip', $karyawan->nip) }}" class="mt-1 block w-full rounded-md border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" required maxlength="20">
                @error('nip')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Role</label>
                <select name="role" id="role" class="mt-1 block w-full rounded-md border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" required>
                    <option value="" disabled selected>Pilih role</option>
                    <option value="admin" {{ old('role', $karyawan->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kasir" {{ old('role', $karyawan->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-slate-300">{{ $mode === 'create' ? 'Password' : 'Password Baru (opsional)' }}</label>
                <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" {{ $mode === 'create' ? 'required' : '' }}>
                @error('password')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('karyawan.index') }}" class="rounded-md border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500 px-4 py-2 text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700">Batal</a>
                <button type="submit" class="rounded-md bg-indigo-600 dark:bg-indigo-500 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 dark:hover:bg-indigo-600">{{ $mode === 'create' ? 'Simpan' : 'Perbarui' }}</button>
            </div>
        </form>
    </div>
</x-admin-layout>

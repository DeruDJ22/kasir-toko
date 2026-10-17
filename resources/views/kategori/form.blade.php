<x-admin-layout :title="$mode === 'create' ? 'Tambah Kategori' : 'Edit Kategori'">
    <x-slot name="header">
        <a href="{{ route('kategori.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Kategori</a>
        <span class="px-1 text-gray-400">/</span>
        <span>{{ $mode === 'create' ? 'Tambah' : 'Edit' }}</span>
    </x-slot>

    <div class="max-w-2xl rounded-lg border border-gray-200 bg-white p-6">
        <form method="POST" action="{{ $mode === 'create' ? route('kategori.store') : route('kategori.update', $kategori) }}" class="space-y-4">
            @csrf
            @if ($mode === 'edit')
                @method('PUT')
            @endif

            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                <input
                    type="text"
                    name="nama"
                    id="nama"
                    value="{{ old('nama', $kategori->nama) }}"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    required
                    maxlength="100"
                >
                @error('nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea
                    name="deskripsi"
                    id="deskripsi"
                    rows="3"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    maxlength="500"
                >{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('kategori.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    {{ $mode === 'create' ? 'Simpan' : 'Perbarui' }}
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>

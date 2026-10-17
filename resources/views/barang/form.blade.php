<x-admin-layout :title="$mode === 'create' ? 'Tambah Barang' : 'Edit Barang'">
    <x-slot name="header">
        <a href="{{ route('barang.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Barang</a>
        <span class="px-1 text-gray-400">/</span>
        <span>{{ $mode === 'create' ? 'Tambah' : 'Edit' }}</span>
    </x-slot>

    <div class="max-w-2xl rounded-lg border border-gray-200 bg-white p-6">
        <form method="POST" action="{{ $mode === 'create' ? route('barang.store') : route('barang.update', $barang) }}" class="space-y-4">
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
                    value="{{ old('nama', $barang->nama) }}"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    required
                    maxlength="100"
                >
                @error('nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                <select
                    name="kategori_id"
                    id="kategori_id"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    required
                >
                    <option value="" disabled @if (old('kategori_id', $barang->kategori_id) === null) selected @endif>Pilih kategori</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" @selected(old('kategori_id', $barang->kategori_id) == $kategori->id)>{{ $kategori->nama }}</option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="harga" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                <input
                    type="number"
                    name="harga"
                    id="harga"
                    value="{{ old('harga', $barang->harga) }}"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    required
                    min="1"
                    step="1"
                >
                <p class="mt-1 text-xs text-gray-500">Masukkan harga dalam rupiah, tanpa desimal.</p>
                @error('harga')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
                <input
                    type="number"
                    name="stok"
                    id="stok"
                    value="{{ old('stok', $barang->stok) }}"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    required
                    min="0"
                    step="1"
                >
                @error('stok')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('barang.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    {{ $mode === 'create' ? 'Simpan' : 'Perbarui' }}
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>

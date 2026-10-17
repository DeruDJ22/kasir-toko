<x-admin-layout title="Kategori">
    <x-slot name="header">Kategori</x-slot>

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('kategori.index') }}" class="flex w-full gap-2 sm:max-w-xs">
            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Cari kategori..."
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
            >
            <button type="submit" class="rounded-md bg-gray-800 px-3 py-2 text-sm font-medium text-white hover:bg-gray-700">Cari</button>
        </form>

        <a href="{{ route('kategori.create') }}" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Tambah Kategori</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left font-medium text-gray-600">Nama</th>
                    <th scope="col" class="px-4 py-3 text-left font-medium text-gray-600">Deskripsi</th>
                    <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600">Jumlah Barang</th>
                    <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($kategoris as $kategori)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $kategori->nama }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $kategori->deskripsi ?: '-' }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ $kategori->barang_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('kategori.edit', $kategori) }}" class="font-medium text-indigo-600 hover:text-indigo-800">Edit</a>
                            <form method="POST" action="{{ route('kategori.destroy', $kategori) }}" class="inline" onsubmit="return confirm('Hapus kategori {{ $kategori->nama }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-2 font-medium text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-500">
                            @if ($search !== '')
                                Tidak ada kategori yang cocok dengan "{{ $search }}".
                            @else
                                Belum ada kategori. Tambahkan kategori pertama untuk mulai mengelola barang.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $kategoris->links() }}
    </div>
</x-admin-layout>

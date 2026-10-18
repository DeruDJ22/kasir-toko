<x-admin-layout title="Kategori">
    <x-slot name="header">Kategori</x-slot>

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('kategori.index') }}" class="flex w-full gap-2 sm:max-w-xs">
            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Cari kategori..."
                class="w-full rounded-md border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
            >
            <button type="submit" class="rounded-md bg-gray-800 dark:bg-slate-700 px-3 py-2 text-sm font-medium text-white hover:bg-gray-700 dark:hover:bg-slate-600">Cari</button>
        </form>

        <a href="{{ route('kategori.create') }}" class="inline-flex items-center justify-center rounded-md bg-indigo-600 dark:bg-indigo-500 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 dark:hover:bg-indigo-600">Tambah Kategori</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700 text-sm">
            <thead class="bg-gray-50 dark:bg-slate-700/50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left font-medium text-gray-600 dark:text-slate-300">Nama</th>
                    <th scope="col" class="px-4 py-3 text-left font-medium text-gray-600 dark:text-slate-300">Deskripsi</th>
                    <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600 dark:text-slate-300">Jumlah Barang</th>
                    <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600 dark:text-slate-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                @forelse ($kategoris as $kategori)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800 dark:text-slate-100">{{ $kategori->nama }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-slate-300">{{ $kategori->deskripsi ?: '-' }}</td>
                        <td class="px-4 py-3 text-right text-gray-600 dark:text-slate-300">{{ $kategori->barang_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('kategori.edit', $kategori) }}" class="font-medium text-indigo-600 hover:text-indigo-800 dark:hover:text-indigo-300">Edit</a>
                            <form method="POST" action="{{ route('kategori.destroy', $kategori) }}" class="inline js-delete-form" data-confirm="Hapus kategori {{ $kategori->nama }}?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-2 font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-slate-400">
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

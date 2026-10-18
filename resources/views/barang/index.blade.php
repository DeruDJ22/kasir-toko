<x-admin-layout title="Barang">
    <x-slot name="header">Barang</x-slot>

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('barang.index') }}" class="flex w-full gap-2 sm:max-w-xs">
            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Cari barang..."
                class="w-full rounded-md border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
            >
            <button type="submit" class="rounded-md bg-gray-800 dark:bg-slate-700 px-3 py-2 text-sm font-medium text-white hover:bg-gray-700 dark:hover:bg-slate-600">Cari</button>
        </form>

        <a href="{{ route('barang.create') }}" class="inline-flex items-center justify-center rounded-md bg-indigo-600 dark:bg-indigo-500 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 dark:hover:bg-indigo-600">Tambah Barang</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700 text-sm">
            <thead class="bg-gray-50 dark:bg-slate-700/50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left font-medium text-gray-600 dark:text-slate-300">Nama</th>
                    <th scope="col" class="px-4 py-3 text-left font-medium text-gray-600 dark:text-slate-300">Kategori</th>
                    <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600 dark:text-slate-300">Harga</th>
                    <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600 dark:text-slate-300">Stok</th>
                    <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600 dark:text-slate-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                @forelse ($barangs as $barang)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800 dark:text-slate-100">{{ $barang->nama }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-slate-300">{{ $barang->kategori?->nama ?? '-' }}</td>
                        <td class="px-4 py-3 text-right text-gray-600 dark:text-slate-300">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-gray-600 dark:text-slate-300">{{ $barang->stok }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('barang.edit', $barang) }}" class="font-medium text-indigo-600 hover:text-indigo-800 dark:hover:text-indigo-300">Edit</a>
                            <form method="POST" action="{{ route('barang.destroy', $barang) }}" class="inline js-delete-form" data-confirm="Hapus barang {{ $barang->nama }}?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-2 font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-slate-400">
                            @if ($search !== '')
                                Tidak ada barang yang cocok dengan "{{ $search }}".
                            @else
                                Belum ada barang. Tambahkan barang pertama untuk mulai berjualan.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $barangs->links() }}
    </div>
</x-admin-layout>

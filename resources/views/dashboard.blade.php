<x-admin-layout title="Dashboard">
    <x-slot name="header">Dashboard</x-slot>

    <div class="grid grid-cols-2 gap-3 sm:max-w-md">
        <div class="rounded-md border border-gray-200 bg-white p-4">
            <p class="text-xs text-gray-500">Penjualan hari ini</p>
            <p class="mt-1 text-lg font-semibold text-gray-800">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-md border border-gray-200 bg-white p-4">
            <p class="text-xs text-gray-500">Transaksi hari ini</p>
            <p class="mt-1 text-lg font-semibold text-gray-800">{{ $jumlahTransaksi }}</p>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg border border-gray-200 bg-white">
        <div class="border-b border-gray-200 px-4 py-3">
            <h2 class="text-sm font-semibold text-gray-800">Stok rendah (&le; 10)</h2>
        </div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left font-medium text-gray-600">Barang</th>
                    <th scope="col" class="px-4 py-3 text-left font-medium text-gray-600">Kategori</th>
                    <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600">Stok</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($stokRendah as $barang)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $barang->nama }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $barang->kategori?->nama ?? '-' }}</td>
                        <td class="px-4 py-3 text-right text-gray-800">{{ $barang->stok }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-gray-500">Tidak ada barang stok rendah.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>

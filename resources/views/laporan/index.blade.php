<x-admin-layout title="Laporan">
    <x-slot name="header">Laporan</x-slot>

    @php
        $presets = [
            'hari_ini' => 'Hari ini',
            'kemarin' => 'Kemarin',
            'bulan_ini' => 'Bulan ini',
            'bulan_lalu' => 'Bulan lalu',
            'custom' => 'Custom',
        ];
    @endphp

    <div class="mb-4 flex flex-wrap items-center gap-2">
        @foreach ($presets as $key => $label)
            <a
                href="{{ $key === 'custom' ? route('laporan.index', ['preset' => 'custom', 'start' => $range['start'], 'end' => $range['end']]) : route('laporan.index', ['preset' => $key]) }}"
                class="rounded-md px-3 py-1.5 text-sm font-medium {{ $preset === $key ? 'bg-indigo-600 dark:bg-indigo-500 text-white' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-300 border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500 hover:bg-gray-50 dark:hover:bg-slate-700' }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if ($preset === 'custom')
        <form method="GET" action="{{ route('laporan.index') }}" class="mb-4 flex flex-wrap items-end gap-2 rounded-md border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-3">
            <input type="hidden" name="preset" value="custom">
            <div>
                <label for="start" class="block text-xs font-medium text-gray-600 dark:text-slate-300">Dari</label>
                <input type="date" id="start" name="start" value="{{ $range['start'] }}" class="mt-1 rounded-md border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500 px-3 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label for="end" class="block text-xs font-medium text-gray-600 dark:text-slate-300">Sampai</label>
                <input type="date" id="end" name="end" value="{{ $range['end'] }}" class="mt-1 rounded-md border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500 px-3 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
            <button type="submit" class="rounded-md bg-gray-800 dark:bg-slate-700 px-3 py-1.5 text-sm font-medium text-white hover:bg-gray-700 dark:hover:bg-slate-600">Terapkan</button>
        </form>
    @endif

    <div class="mb-6 grid grid-cols-2 gap-3 sm:max-w-md">
        <div class="rounded-md border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4">
            <p class="text-xs text-gray-500 dark:text-slate-400">Total penjualan</p>
            <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-slate-100">Rp {{ number_format($total, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4">
            <p class="text-xs text-gray-500 dark:text-slate-400">Jumlah transaksi</p>
            <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-slate-100">{{ $jumlah }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800">
        <div class="border-b border-gray-200 dark:border-slate-700 px-4 py-3">
            <h2 class="text-sm font-semibold text-gray-800 dark:text-slate-100">Barang terlaris (5 teratas)</h2>
        </div>
        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700 text-sm">
            <thead class="bg-gray-50 dark:bg-slate-700/50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left font-medium text-gray-600 dark:text-slate-300">Barang</th>
                    <th scope="col" class="px-4 py-3 text-left font-medium text-gray-600 dark:text-slate-300">Kategori</th>
                    <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600 dark:text-slate-300">Terjual (pcs)</th>
                    <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600 dark:text-slate-300">Transaksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                @forelse ($bestSelling as $item)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800 dark:text-slate-100">{{ $item->barang->nama ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-slate-300">{{ $item->barang->kategori?->nama ?? '-' }}</td>
                        <td class="px-4 py-3 text-right text-gray-800 dark:text-slate-100">{{ $item->total_qty }}</td>
                        <td class="px-4 py-3 text-right text-gray-800 dark:text-slate-100">{{ $item->transactions }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-slate-400">Tidak ada data penjualan pada rentang ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
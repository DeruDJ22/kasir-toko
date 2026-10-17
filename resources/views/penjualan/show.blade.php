<x-admin-layout title="Penjualan #{{ str_pad((string) $penjualan->id, 6, '0', STR_PAD_LEFT) }}">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('penjualan.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">&larr; Kembali</a>
            <span>Penjualan #{{ str_pad((string) $penjualan->id, 6, '0', STR_PAD_LEFT) }}</span>
        </div>
    </x-slot>

    <div class="grid gap-4 lg:grid-cols-[1fr_280px]">
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left font-medium text-gray-600">Barang</th>
                        <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600">Harga</th>
                        <th scope="col" class="px-4 py-3 text-center font-medium text-gray-600">Qty</th>
                        <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($penjualan->details as $detail)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $detail->barang?->nama ?? 'Barang dihapus' }}</td>
                            <td class="px-4 py-3 text-right text-gray-600">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center text-gray-600">{{ $detail->qty }}</td>
                            <td class="px-4 py-3 text-right text-gray-800">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="h-fit rounded-lg border border-gray-200 bg-white p-4">
            <h2 class="text-sm font-semibold text-gray-800">Ringkasan</h2>

            <dl class="mt-3 space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Tanggal</dt>
                    <dd class="text-gray-800">{{ $penjualan->tanggal->format('d/m/Y') }} {{ $penjualan->created_at->format('H:i') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Kasir</dt>
                    <dd class="text-gray-800">{{ $penjualan->kasir?->name ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Total</dt>
                    <dd class="font-semibold text-gray-800">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Diterima</dt>
                    <dd class="text-gray-800">Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Kembali</dt>
                    <dd class="font-semibold text-gray-800">Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</dd>
                </div>
            </dl>

            <a href="{{ route('pos.receipt', ['penjualan' => $penjualan, 'print' => 1]) }}" target="_blank" rel="noopener" class="mt-4 block w-full rounded-md bg-indigo-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-indigo-700">
                Cetak struk
            </a>
        </div>
    </div>
</x-admin-layout>
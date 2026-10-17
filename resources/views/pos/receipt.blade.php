<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Struk {{ $penjualan->id }}</title>

        @vite(['resources/css/app.css'])
    </head>
    <body class="receipt-body">
        <article class="receipt">
            <header class="receipt-header">
                <h1 class="receipt-title">{{ config('app.store_name') }}</h1>
                @if (config('app.store_address'))
                    <p class="receipt-address">{{ config('app.store_address') }}</p>
                @endif
            </header>

            <dl class="receipt-meta">
                <div class="receipt-meta-row">
                    <dt>No</dt>
                    <dd>#{{ str_pad((string) $penjualan->id, 6, '0', STR_PAD_LEFT) }}</dd>
                </div>
                <div class="receipt-meta-row">
                    <dt>Tanggal</dt>
                    <dd>{{ $penjualan->tanggal->format('d/m/Y') }} {{ $penjualan->created_at->format('H:i') }}</dd>
                </div>
                <div class="receipt-meta-row">
                    <dt>Kasir</dt>
                    <dd>{{ $penjualan->kasir->name }}</dd>
                </div>
            </dl>

            <ul class="receipt-items">
                @foreach ($penjualan->details as $detail)
                    <li class="receipt-item">
                        <p class="receipt-item-name">{{ $detail->barang->nama }}</p>
                        <div class="receipt-item-line">
                            <span>{{ $detail->qty }} x {{ number_format($detail->harga, 0, ',', '.') }}</span>
                            <span>{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>

            <dl class="receipt-totals">
                <div class="receipt-total-row">
                    <dt>Total</dt>
                    <dd>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</dd>
                </div>
                <div class="receipt-total-row">
                    <dt>Diterima</dt>
                    <dd>Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</dd>
                </div>
                <div class="receipt-total-row receipt-total-change">
                    <dt>Kembali</dt>
                    <dd>Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</dd>
                </div>
            </dl>

            <footer class="receipt-footer">
                <p>Terima kasih atas kunjungannya.</p>
            </footer>

            <div class="receipt-actions">
                <button type="button" onclick="window.print()" class="w-full rounded-md bg-indigo-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-indigo-700">
                    Cetak struk
                </button>
                <a href="{{ route('pos.index') }}" class="w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-center text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Transaksi baru
                </a>
            </div>
        </article>

        <script>
            window.addEventListener('load', function () {
                @if ($autoPrint ?? false)
                window.print();
                @endif
            });
        </script>
    </body>
</html>
<?php

namespace App\Actions;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BuatPenjualan
{
    public function handle(User $kasir, array $items, int $bayar): Penjualan
    {
        return DB::transaction(function () use ($kasir, $items, $bayar) {
            $barangs = Barang::query()
                ->whereIn('id', collect($items)->pluck('id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;
            $details = [];

            foreach ($items as $item) {
                $barang = $barangs->get($item['id']);

                if (! $barang) {
                    throw ValidationException::withMessages([
                        'items' => 'Ada barang yang sudah tidak tersedia, muat ulang halaman.',
                    ]);
                }

                $qty = (int) $item['qty'];

                if ($barang->stok < $qty) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$barang->nama} tinggal {$barang->stok}.",
                    ]);
                }

                $subtotal = $barang->harga * $qty;
                $total += $subtotal;
                $details[] = ['barang' => $barang, 'qty' => $qty, 'subtotal' => $subtotal];
            }

            if ($bayar < $total) {
                throw ValidationException::withMessages([
                    'bayar' => 'Uang diterima kurang dari total belanja.',
                ]);
            }

            $penjualan = Penjualan::create([
                'kasir_id' => $kasir->id,
                'tanggal' => now()->toDateString(),
                'total' => $total,
                'bayar' => $bayar,
                'kembalian' => $bayar - $total,
            ]);

            foreach ($details as $detail) {
                $penjualan->details()->create([
                    'barang_id' => $detail['barang']->id,
                    'qty' => $detail['qty'],
                    'harga' => $detail['barang']->harga,
                    'subtotal' => $detail['subtotal'],
                ]);

                $detail['barang']->stok -= $detail['qty'];
                $detail['barang']->save();
            }

            return $penjualan;
        });
    }
}

<?php

namespace App\Http\Controllers;

use App\Actions\BuatPenjualan;
use App\Http\Requests\CheckoutRequest;
use App\Models\Barang;
use App\Models\Penjualan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(): View
    {
        return view('pos.index');
    }

    public function pay(CheckoutRequest $request, BuatPenjualan $buatPenjualan): JsonResponse
    {
        $penjualan = $buatPenjualan->handle(
            $request->user(),
            $request->validated('items'),
            (int) $request->validated('bayar'),
        );

        return response()->json([
            'data' => [
                'id' => $penjualan->id,
                'receipt_url' => route('pos.receipt', ['penjualan' => $penjualan, 'print' => 1]),
                'total' => $penjualan->total,
                'bayar' => $penjualan->bayar,
                'kembalian' => $penjualan->kembalian,
            ],
        ], 201);
    }

    public function receipt(Request $request, Penjualan $penjualan): View
    {
        $user = $request->user();

        abort_unless($user->role === 'admin' || $penjualan->kasir_id === $user->id, 403);

        $penjualan->loadMissing(['kasir', 'details.barang']);

        return view('pos.receipt', [
            'penjualan' => $penjualan,
            'autoPrint' => $request->boolean('print'),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $keyword = trim((string) $request->query('q', ''));

        $barangs = Barang::query()
            ->with('kategori')
            ->where('stok', '>', 0)
            ->when($keyword !== '', fn ($q) => $q->where('nama', 'like', "%{$keyword}%"))
            ->orderBy('nama')
            ->limit(20)
            ->get()
            ->map(fn (Barang $barang) => [
                'id' => $barang->id,
                'nama' => $barang->nama,
                'harga' => $barang->harga,
                'stok' => $barang->stok,
                'kategori' => $barang->kategori?->nama,
            ]);

        return response()->json([
            'data' => $barangs,
        ]);
    }
}
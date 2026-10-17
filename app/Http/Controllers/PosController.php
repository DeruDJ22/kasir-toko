<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(): View
    {
        return view('pos.index');
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
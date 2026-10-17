<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriRequest;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $kategoris = Kategori::query()
            ->withCount('barang')
            ->when($search !== '', fn ($q) => $q->where('nama', 'like', "%{$search}%"))
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('kategori.index', [
            'kategoris' => $kategoris,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('kategori.form', [
            'kategori' => new Kategori(),
            'mode' => 'create',
        ]);
    }

    public function store(KategoriRequest $request): RedirectResponse
    {
        Kategori::create($request->validated());

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori): View
    {
        return view('kategori.form', [
            'kategori' => $kategori,
            'mode' => 'edit',
        ]);
    }

    public function update(KategoriRequest $request, Kategori $kategori): RedirectResponse
    {
        $kategori->update($request->validated());

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori): RedirectResponse
    {
        if ($kategori->barang()->exists()) {
            return redirect()
                ->route('kategori.index')
                ->with('error', 'Kategori masih memiliki barang aktif. Hapus atau pindahkan barang terlebih dahulu.');
        }

        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}

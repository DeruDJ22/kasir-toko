<?php

namespace App\Http\Controllers;

use App\Http\Requests\KaryawanRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class KaryawanController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $karyawans = User::query()
            ->when($search !== '', fn ($query) => $query->where(
                fn ($sub) => $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
            ))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('karyawan.index', [
            'karyawans' => $karyawans,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('karyawan.form', [
            'karyawan' => new User(),
            'mode' => 'create',
        ]);
    }

    public function store(KaryawanRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function edit(User $karyawan): View
    {
        return view('karyawan.form', [
            'karyawan' => $karyawan,
            'mode' => 'edit',
        ]);
    }

    public function update(KaryawanRequest $request, User $karyawan): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $karyawan->update($data);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui.');
    }

    public function destroy(User $karyawan): RedirectResponse
    {
        if ($karyawan->id === Auth::id()) {
            return redirect()->route('karyawan.index')->with('error', 'Akun yang sedang aktif tidak dapat dihapus.');
        }

        if ($karyawan->penjualans()->exists()) {
            return redirect()->route('karyawan.index')->with('error', 'Karyawan tidak dapat dihapus karena memiliki riwayat transaksi penjualan.');
        }

        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}

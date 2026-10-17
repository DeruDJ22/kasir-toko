<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenjualanController extends Controller
{
    public function index(Request $request): View
    {
        $range = $this->resolveRange($request);
        $user = $request->user();

        $start = \Illuminate\Support\Carbon::parse($range['start'])->startOfDay();
        $end = \Illuminate\Support\Carbon::parse($range['end'])->endOfDay();

        $penjualans = Penjualan::query()
            ->with('kasir')
            ->whereBetween('tanggal', [$start, $end])
            ->when($user->role !== 'admin', fn ($q) => $q->where('kasir_id', $user->id))
            ->latest('tanggal')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $totals = Penjualan::query()
            ->whereBetween('tanggal', [$start, $end])
            ->when($user->role !== 'admin', fn ($q) => $q->where('kasir_id', $user->id))
            ->selectRaw('COUNT(*) as jumlah, COALESCE(SUM(total), 0) as total')
            ->first();

        return view('penjualan.index', [
            'penjualans' => $penjualans,
            'range' => $range,
            'preset' => $request->query('preset', 'bulan_ini'),
            'total' => (int) ($totals->total ?? 0),
            'jumlah' => (int) ($totals->jumlah ?? 0),
        ]);
    }

    public function show(Request $request, Penjualan $penjualan): View
    {
        $user = $request->user();
        abort_unless($user->role === 'admin' || $penjualan->kasir_id === $user->id, 403);

        $penjualan->loadMissing(['kasir', 'details.barang']);

        return view('penjualan.show', [
            'penjualan' => $penjualan,
        ]);
    }

    private function resolveRange(Request $request): array
    {
        $preset = $request->query('preset', 'bulan_ini');
        $today = now()->startOfDay();

        return match ($preset) {
            'hari_ini' => [
                'label' => 'Hari ini',
                'start' => $today->toDateString(),
                'end' => $today->copy()->endOfDay()->toDateString(),
            ],
            'kemarin' => [
                'label' => 'Kemarin',
                'start' => $today->copy()->subDay()->toDateString(),
                'end' => $today->copy()->subDay()->endOfDay()->toDateString(),
            ],
            'bulan_lalu' => [
                'label' => 'Bulan lalu',
                'start' => $today->copy()->subMonthNoOverflow()->startOfMonth()->toDateString(),
                'end' => $today->copy()->subMonthNoOverflow()->endOfMonth()->toDateString(),
            ],
            'custom' => $this->customRange($request, $today),
            default => [
                'label' => 'Bulan ini',
                'start' => $today->copy()->startOfMonth()->toDateString(),
                'end' => $today->copy()->endOfMonth()->toDateString(),
            ],
        };
    }

    private function customRange(Request $request, $today): array
    {
        $start = $request->query('start', $today->copy()->startOfMonth()->toDateString());
        $end = $request->query('end', $today->toDateString());

        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $start) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $end)) {
            $start = $today->copy()->startOfMonth()->toDateString();
            $end = $today->toDateString();
        }

        if (strcmp($start, $end) > 0) {
            [$start, $end] = [$end, $start];
        }

        return [
            'label' => 'Custom',
            'start' => $start,
            'end' => $end,
        ];
    }
}
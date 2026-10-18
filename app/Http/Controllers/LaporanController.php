<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $range = $this->resolveRange($request);
        $start = Carbon::parse($range['start'])->startOfDay();
        $end = Carbon::parse($range['end'])->endOfDay();

        $penjualans = Penjualan::query()
            ->whereBetween('tanggal', [$start, $end])
            ->get();

        $total = $penjualans->sum('total');
        $jumlah = $penjualans->count();

        $bestSelling = $this->bestSellingItems($start, $end);

        return view('laporan.index', [
            'range' => $range,
            'preset' => $request->query('preset', 'bulan_ini'),
            'total' => (int) $total,
            'jumlah' => (int) $jumlah,
            'bestSelling' => $bestSelling,
        ]);
    }

    private function resolveRange(Request $request): array
    {
        $preset = $request->query('preset', 'bulan_ini');
        $today = now()->startOfDay();

        return match ($preset) {
            'hari_ini' => [
                'start' => $today->toDateString(),
                'end' => $today->copy()->endOfDay()->toDateString(),
            ],
            'kemarin' => [
                'start' => $today->copy()->subDay()->toDateString(),
                'end' => $today->copy()->subDay()->endOfDay()->toDateString(),
            ],
            'bulan_lalu' => [
                'start' => $today->copy()->subMonthNoOverflow()->startOfMonth()->toDateString(),
                'end' => $today->copy()->subMonthNoOverflow()->endOfMonth()->toDateString(),
            ],
            'custom' => $this->customRange($request, $today),
            default => [
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
            'start' => $start,
            'end' => $end,
        ];
    }

    private function bestSellingItems($start, $end)
    {
        return PenjualanDetail::query()
            ->whereHas('penjualan', fn ($q) => $q->whereBetween('tanggal', [$start, $end]))
            ->select('barang_id', PenjualanDetail::raw('SUM(qty) as total_qty'), PenjualanDetail::raw('COUNT(*) as transactions'))
            ->with('barang')
            ->groupBy('barang_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'barang' => $item->barang,
                    'total_qty' => (int) $item->total_qty,
                    'transactions' => (int) $item->transactions,
                ];
            });
    }
}
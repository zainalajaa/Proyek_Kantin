<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{

    public function index(Request $request)
    {
        $query = Penjualan::query();

        // FILTER HARIAN
        if ($request->filter === 'harian') {
            $query->whereDate('waktu', Carbon::today());
        }

        // FILTER MINGGUAN
        if ($request->filter === 'mingguan') {
            $query->whereBetween('waktu', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);
        }

        // FILTER BULANAN
        if ($request->filter === 'bulanan') {
            $query->whereMonth('waktu', Carbon::now()->month)
                ->whereYear('waktu', Carbon::now()->year);
        }

        $penjualan = $query
            ->orderBy('waktu', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('penjualan.tabel', compact('penjualan'));
    }

    public function show($id)
    {
        $penjualan = \App\Models\Penjualan::with('details')->findOrFail($id);

        return view('penjualan.detail', compact('penjualan'));
    }

}

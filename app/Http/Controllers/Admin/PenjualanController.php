<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualan = \App\Models\Penjualan::orderBy('waktu', 'desc')->paginate(15);

        return view('penjualan.tabel', compact('penjualan'));
    }

    public function show($id)
    {
        $penjualan = \App\Models\Penjualan::with('details')->findOrFail($id);

        return view('penjualan.detail', compact('penjualan'));
    }

}



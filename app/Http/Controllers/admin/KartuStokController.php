<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KartuStok;
use Illuminate\Http\Request;

class KartuStokController extends Controller
{
    /**
     * Menampilkan halaman laporan kartu stok.
     */
    public function index()
    {
        $kartuStok = KartuStok::with('barang.satuan')
            ->orderBy('created_at', 'desc')
            ->orderBy('idkartu_stok', 'desc')
            ->get();

        return view('admin.kartustok.index', compact('kartuStok'));
    }
}
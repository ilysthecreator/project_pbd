<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // <--- Tambahkan ini

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $summary = (object) [
            'total_barang' => DB::table('barang')->count(),
            'total_satuan' => DB::table('satuan')->count(),
            'total_vendor' => DB::table('vendor')->count(),
            'total_user' => DB::table('user')->count(),
            'total_penjualan' => DB::table('penjualan')->where('status', 'S')->count(),
            'total_pengadaan' => DB::table('pengadaan')->where('status', '!=', 'C')->count(),
            'total_penerimaan' => DB::table('penerimaan')->count(),
        ];

        // (Sisa kode query Anda biarkan tetap sama...)
        $barangTerbaru = DB::table('barang')
            ->select('barang.*', 'satuan.nama_satuan')
            ->leftJoin('satuan', 'barang.idsatuan', '=', 'satuan.idsatuan')
            ->orderBy('barang.idbarang', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                $item->satuan = (object) ['nama_satuan' => $item->nama_satuan];
                return $item;
            });

        $satuanTerbaru = DB::table('satuan')
            ->select(
                'satuan.*',
                DB::raw('(SELECT COUNT(*) FROM barang WHERE barang.idsatuan = satuan.idsatuan) as jumlah_barang')
            )
            ->orderBy('satuan.idsatuan', 'desc')
            ->limit(5)
            ->get();

        $vendorTerbaru = DB::table('vendor')
            ->orderBy('idvendor', 'desc')
            ->limit(5)
            ->get()
            ->map(function($vendor) {
                $vendor->status_text = $vendor->status == 1 ? 'Aktif' : 'Nonaktif';
                return $vendor;
            });

        $userTerbaru = DB::table('user')
            ->orderBy('iduser', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'summary',
            'barangTerbaru',
            'satuanTerbaru',
            'vendorTerbaru',
            'userTerbaru'
        ));
    }
}
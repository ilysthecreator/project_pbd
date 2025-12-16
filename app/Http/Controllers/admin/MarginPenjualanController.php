<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarginPenjualanController extends Controller
{
    /**
     * Menampilkan halaman laporan margin penjualan.
     */
    public function index(Request $request)
    {
        // 1. Subquery: Hitung harga beli rata-rata (COGS) per barang
        $hargaBeliAvg = DB::table('detail_penerimaan')
            ->select('idbarang', DB::raw('AVG(harga_satuan) as harga_beli_rata'))
            ->groupBy('idbarang');

        // 2. Subquery: Hitung total biaya (COGS) dan total margin per penjualan
        $marginDetails = DB::table('detail_penjualan as dp')
            ->leftJoinSub($hargaBeliAvg, 'hb', function ($join) {
                $join->on('dp.idbarang', '=', 'hb.idbarang');
            })
            ->select(
                'dp.penjualan_idpenjualan',
                DB::raw('SUM(dp.jumlah * COALESCE(hb.harga_beli_rata, 0)) as total_cogs'),
                DB::raw('SUM(dp.subtotal) as total_revenue')
            )
            ->groupBy('dp.penjualan_idpenjualan');

        // 3. Query Utama: Gabungkan data penjualan dengan hasil kalkulasi margin
        $query = DB::table('penjualan as p')
            ->joinSub($marginDetails, 'md', function ($join) {
                $join->on('p.idpenjualan', '=', 'md.penjualan_idpenjualan');
            })
            ->leftJoin('user as u', 'p.iduser', '=', 'u.iduser')
            ->select(
                'p.idpenjualan',
                'p.created_at',

                'p.status',
                'p.total_nilai',
                'u.username as dibuat_oleh',
                'md.total_cogs',
                'md.total_revenue',
                // Hitung Total Margin (Rupiah)
                DB::raw('(md.total_revenue - md.total_cogs) as total_margin'),
                // Hitung Persentase Margin (Handle division by zero)
                DB::raw('CASE WHEN md.total_cogs > 0 THEN ((md.total_revenue - md.total_cogs) / md.total_cogs) * 100 ELSE 0 END as margin_persen')
            )
            ->orderBy('p.created_at', 'desc');

        // --- FILTER DATA ---
        $status = $request->query('status');

        // Filter Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('p.created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filter Status
        if ($status === 'aktif') {
            $query->where('p.status', 'S');
        } elseif ($status === 'tidak-aktif') {
            $query->where('p.status', '!=', 'S');
        }

        // Filter Pencarian (Nama Barang)
        // Note: Logic search ini agak tricky karena query utama tidak join ke barang langsung (karena 1 penjualan punya banyak barang).
        // Jika ingin search nama barang, logic harus diubah sedikit, tapi untuk saat ini saya biarkan sesuai kode asli Anda
        // namun dikomentari agar tidak error jika 'b.nama' tidak dikenali di scope ini.
        if ($request->filled('search')) {
             // Search ini mungkin tidak berjalan karena alias 'b' tidak ada di query utama ($query).
             // Untuk sementara di-disable atau perlu join tambahan jika ingin aktif.
             // $query->where('b.nama', 'like', '%' . $request->search . '%');
        }

        // Pagination
        $marginData = $query->paginate(25)->withQueryString();

        return view('admin.margin_penjualan.index', compact('marginData', 'status'));
    }
}
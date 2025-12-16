<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturPenjualan;
use App\Models\DetailReturPenjualan;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReturPenjualanController extends Controller
{
    /**
     * Menampilkan daftar retur penjualan.
     */
    public function index()
    {
        $returPenjualan = ReturPenjualan::with('penjualan.user', 'user')
            ->withSum('details as total_item_diretur', 'jumlah')
            ->orderBy('created_at', 'desc')
            ->get();

        // Ini akan error jika view 'admin.retur_penjualan.index' belum dibuat
        return view('admin.retur_penjualan.index', compact('returPenjualan'));
    }

    /**
     * Menampilkan form untuk membuat retur baru.
     */
    public function create()
    {
        // Ambil penjualan yang belum pernah diretur
        // <-- PERBAIKAN: Menghapus filter 'status' karena tidak ada di tabel penjualan
        $penjualanList = Penjualan::whereDoesntHave('retur') 
            ->orderBy('created_at', 'desc')
            ->get();

        // Ini akan error jika view 'admin.retur_penjualan.create' belum dibuat
        return view('admin.retur_penjualan.create', compact('penjualanList'));
    }

    /**
     * Menyimpan data retur penjualan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idpenjualan' => 'required|exists:penjualan,idpenjualan',
            'alasan' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.idbarang' => 'required|exists:barang,idbarang',
            'items.*.jumlah' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $penjualan = Penjualan::with('details')->findOrFail($request->idpenjualan);
            $totalNilaiRetur = 0;
            $totalItemDiretur = 0;

            // 1. Buat master retur
            $retur = ReturPenjualan::create([
                'idpenjualan' => $penjualan->idpenjualan,
                'iduser' => Auth::id() ?? 1, // Ganti dengan user yang login
                'alasan' => $request->alasan,
                'total_nilai_retur' => 0, // Akan diupdate nanti
            ]);

            // 2. Proses detail item yang diretur
            foreach ($request->items as $item) {
                $jumlahRetur = (int)$item['jumlah'];
                if ($jumlahRetur <= 0) {
                    continue;
                }

                $totalItemDiretur += $jumlahRetur;

                // Ambil detail penjualan asli untuk validasi dan harga
                $detailPenjualan = $penjualan->details->firstWhere('idbarang', $item['idbarang']);
                
                // Cek jika barang ada di penjualan & jumlah retur tidak melebihi jumlah beli
                $jumlahBeli = $detailPenjualan ? $detailPenjualan->jumlah : 0;
                if (!$detailPenjualan || $jumlahRetur > $jumlahBeli) {
                    throw new \Exception("Jumlah retur untuk barang melebihi jumlah yang dibeli (Beli: $jumlahBeli, Retur: $jumlahRetur).");
                }

                $hargaSatuan = $detailPenjualan->harga_satuan;
                $subtotal = $jumlahRetur * $hargaSatuan;
                $totalNilaiRetur += $subtotal;

                // Simpan detail retur
                DetailReturPenjualan::create([
                    'idretur' => $retur->idretur,
                    'idbarang' => $item['idbarang'],
                    'jumlah' => $jumlahRetur,
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                ]);

                // 3. Update Kartu Stok (mengembalikan stok)
                $stokSebelumnya = DB::table('kartu_stok')->where('idbarang', $item['idbarang'])->sum(DB::raw('masuk - keluar'));
                
                DB::table('kartu_stok')->insert([
                    'idbarang' => $item['idbarang'],
                    'idtransaksi' => $retur->idretur,
                    'jenis_transaksi' => 'R', // R = Retur Jual
                    'masuk' => $jumlahRetur, // Stok bertambah
                    'keluar' => 0,
                    'stock' => $stokSebelumnya + $jumlahRetur,
                    'created_at' => now(),
                ]);
            }

            if ($totalItemDiretur == 0) {
                throw new \Exception("Tidak ada barang yang diretur. Minimal 1 barang harus diisi jumlahnya.");
            }

            // Update total nilai di master retur
            $retur->update(['total_nilai_retur' => $totalNilaiRetur]);

            DB::commit();

            return redirect()->route('admin.retur_penjualan.show', $retur->idretur)
                ->with('success', 'Data retur penjualan berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan detail retur.
     */
    public function show($id)
    {
        // <-- PERBAIKAN: Menghapus 'penjualan.vendor' karena relasi itu tidak ada
        $retur = ReturPenjualan::with(['penjualan.user', 'user', 'details.barang.satuan'])->findOrFail($id);
        
        // Ini akan error jika view 'admin.retur_penjualan.show' belum dibuat
        return view('admin.retur_penjualan.show', compact('retur'));
    }

    /**
     * API untuk mendapatkan detail penjualan yang bisa diretur.
     */
    public function getPenjualanDetails($id)
    {
        $penjualan = Penjualan::with(['details.barang.satuan'])->findOrFail($id);
        // Di masa depan, Anda bisa menambahkan logika untuk mengecek item yang sudah pernah diretur
        return response()->json($penjualan);
    }
}
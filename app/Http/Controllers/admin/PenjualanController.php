<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Barang;
use App\Models\KartuStok; // Asumsi Anda punya model KartuStok
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    /**
     * Helper function untuk mengambil stok saat ini dari kartu_stok
     */
    private function getStokSaatIni($idbarang)
    {
        $stok = DB::table('kartu_stok')
                    ->where('idbarang', $idbarang)
                    ->sum(DB::raw('masuk - keluar'));
        return $stok ?? 0;
    }

    /**
     * Menampilkan daftar penjualan
     */
    public function index()
    {
        $penjualan = Penjualan::with('user')
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
        return view('admin.penjualan.index', compact('penjualan'));
    }

    /**
     * Menampilkan form kasir (create)
     */
    public function create()
    {
        // Query untuk mengambil stok dari kartu_stok
        $stokSubquery = DB::table('kartu_stok')
            ->select('idbarang', DB::raw('SUM(masuk - keluar) as stok_saat_ini'))
            ->groupBy('idbarang');

        $barang = DB::table('barang as b')
            ->join('satuan as s', 'b.idsatuan', '=', 's.idsatuan')
            ->leftJoinSub($stokSubquery, 'ks', function($join) {
                $join->on('b.idbarang', '=', 'ks.idbarang');
            })
            ->where('b.status', 1)
            ->select(
                'b.idbarang', 
                'b.nama', 
                'b.harga', 
                's.nama_satuan',
                DB::raw('COALESCE(ks.stok_saat_ini, 0) as stok') // Ambil stok dari subquery
            )
            ->orderBy('b.nama')
            ->get();
        
        // Filter barang yang stoknya > 0
        $barang = $barang->filter(function($b) {
            return $b->stok > 0;
        });

        return view('admin.penjualan.create', compact('barang'));
    }

    /**
     * Menyimpan transaksi penjualan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'nullable|string|max:100',
            'idbarang' => 'required|array|min:1',
            'idbarang.*' => 'required|exists:barang,idbarang',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        
        try {
            $subtotal = 0;
            $detailsData = [];
            $kartuStokData = [];
            
            // Validasi Stok dan Hitung Subtotal
            foreach ($request->idbarang as $key => $idbarang) {
                $jumlah_jual = (int) $request->jumlah[$key];
                $barang = Barang::find($idbarang);
                
                // 1. Validasi Stok dari Kartu Stok
                $stok_saat_ini = $this->getStokSaatIni($idbarang);
                if ($stok_saat_ini < $jumlah_jual) {
                    throw new \Exception("Stok barang '{$barang->nama}' tidak mencukupi. Sisa stok: {$stok_saat_ini}");
                }

                $harga_jual = $barang->harga; // Ambil harga dari master barang
                $sub_total_item = $jumlah_jual * $harga_jual;
                $subtotal += $sub_total_item;

                $detailsData[] = [
                    'idbarang' => $idbarang,
                    'jumlah' => $jumlah_jual,
                    'harga_satuan' => $harga_jual,
                    'subtotal' => $sub_total_item, // Sesuai DDL
                ];
            }

            $ppn = $subtotal * 0.11; // PPN 11%
            $total = $subtotal + $ppn;

            // 2. Buat record Penjualan (Master)
            $penjualan = Penjualan::create([
                'iduser' => Auth::id() ?? 1, // Ganti dengan Auth::id()
                'nama_pelanggan' => $request->nama_pelanggan ?? 'Umum',
                'subtotal_nilai' => $subtotal,
                'ppn' => $ppn,
                'total_nilai' => $total, // Total nilai adalah total yang harus dibayar
                'total_bayar' => $total, // Total bayar disamakan dengan total nilai
                'kembalian' => 0, // Kembalian menjadi 0
                'status' => 'S', // Selesai
            ]);

            // 3. Buat record Detail Penjualan
            foreach($detailsData as $data) {
                $data['penjualan_idpenjualan'] = $penjualan->idpenjualan; // Tambah foreign key
                DetailPenjualan::create($data);
            }

            // 4. Masukkan ke Kartu Stok
            foreach ($detailsData as $detail) {
                $stok_sebelumnya = $this->getStokSaatIni($detail['idbarang']);
                DB::table('kartu_stok')->insert([
                    'jenis_transaksi' => 'J', // J = Jual
                    'masuk' => 0,
                    'keluar' => $detail['jumlah'],
                    'stock' => $stok_sebelumnya - $detail['jumlah'],
                    'created_at' => now(),
                    'idtransaksi' => $penjualan->idpenjualan,
                    'idbarang' => $detail['idbarang'],
                ]);
            }

            DB::commit();
            
            return redirect()->route('admin.penjualan.show', $penjualan->idpenjualan)
                ->with('success', 'Transaksi penjualan berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan halaman struk (show)
     */
    public function show($id)
    {
        // Ganti 'details.barang.satuan' sesuai relasi
        $penjualan = Penjualan::with('user', 'details.barang')
                        ->findOrFail($id);

        return view('admin.penjualan.show', compact('penjualan'));
    }

    /**
     * Membatalkan (Void) transaksi
     * Ini tidak menghapus, tapi membalikkan stok.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $penjualan = Penjualan::with('details')->findOrFail($id);

            // 1. Cek apakah sudah di-void
            if ($penjualan->status == 'V') {
                throw new \Exception("Transaksi ini sudah pernah dibatalkan (void).");
            }

            // 2. Kembalikan Stok (buat record baru di kartu_stok)
            foreach ($penjualan->details as $detail) {
                $stok_sebelumnya = $this->getStokSaatIni($detail->idbarang);
                DB::table('kartu_stok')->insert([
                    'jenis_transaksi' => 'R', // R = Retur / Void
                    'masuk' => $detail->jumlah, // Barang masuk kembali
                    'keluar' => 0,
                    'stock' => $stok_sebelumnya + $detail->jumlah,
                    'created_at' => now(),
                    'idtransaksi' => $penjualan->idpenjualan,
                    'idbarang' => $detail->idbarang,
                ]);
            }

            // 3. Update status penjualan
            $penjualan->update(['status' => 'V']); // V = Void

            DB::commit();
            
            return redirect()->route('admin.penjualan.index')
                ->with('success', 'Transaksi penjualan berhasil dibatalkan (void) dan stok dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.penjualan.index')
                ->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
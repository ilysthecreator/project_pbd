<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penerimaan;
use App\Models\DetailPenerimaan;
use App\Models\Pengadaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenerimaanController extends Controller
{
    public function index()
    {
        $penerimaan = Penerimaan::with('pengadaan.vendor', 'user')
            ->withSum('details as total_item_diterima', 'jumlah')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.penerimaan.index', compact('penerimaan'));
    }

    public function create()
    {
        // Ambil pengadaan yang belum Selesai ('S')
        // Di SQL Anda, default status adalah 'S', tapi logic program 
        // biasanya pengadaan baru statusnya 'P' (Proses) atau belum diterima penuh.
        // Kita ambil semua yang memiliki detail.
        $pengadaanList = Pengadaan::with('vendor')
            ->has('details')
            ->where('status', '!=', 'S') // Logic: Hanya tampilkan PO yang belum closed
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.penerimaan.create', compact('pengadaanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idpengadaan' => 'required|exists:pengadaan,idpengadaan',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.idbarang' => 'required|integer|exists:barang,idbarang',
            'items.*.jumlah_terima' => 'required|integer|min:0', 
        ]);

        DB::beginTransaction();

        try {
            $pengadaan = Pengadaan::with('details')->findOrFail($request->idpengadaan);
            
            // Cek apakah pengadaan sudah selesai sepenuhnya
            if ($pengadaan->status == 'S') {
                // Opsional: Anda bisa memblokir atau membiarkan (untuk kasus revisi stok)
                // throw new \Exception("Pengadaan ini sudah berstatus Selesai.");
            }

            // 1. Buat Header Penerimaan
            $penerimaan = Penerimaan::create([
                'idpengadaan' => $pengadaan->idpengadaan,
                'iduser' => Auth::id() ?? 1, // Fallback ke user ID 1 jika null
                'status' => 'S', // Default status Penerimaan 'S'
                // Kolom 'catatan' tidak ada di tabel 'penerimaan', jadi tidak dimasukkan.
                // Jika Anda ingin menyimpan catatan, tambahkan kolom 'catatan' di tabel 'penerimaan'.
            ]);

            $totalDiterimaInput = 0;

            // 2. Loop Items
            foreach ($request->items as $item) {
                $idBarang = $item['idbarang'];
                $jumlahInput = (int)$item['jumlah_terima']; 

                // --- Validasi Over-Receive ---
                $detailPengadaan = $pengadaan->details->where('idbarang', $idBarang)->first();
                
                // [PERBAIKAN] Jika detail pengadaan tidak ditemukan untuk barang ini, lemparkan error.
                if (!$detailPengadaan) {
                    throw new \Exception("Data barang dengan ID {$idBarang} tidak ditemukan pada Pengadaan ini.");
                }

                $jumlahDipesan = $detailPengadaan->jumlah;
                $hargaSatuan = $detailPengadaan->harga_satuan;

                // Hitung total yang SUDAH diterima sebelumnya (dari tabel detail_penerimaan)
                $historyTerima = DetailPenerimaan::whereHas('penerimaan', function($q) use ($pengadaan) {
                    $q->where('idpengadaan', $pengadaan->idpengadaan);
                })->where('idbarang', $idBarang)->sum('jumlah');

                $sisaBolehDiterima = $jumlahDipesan - $historyTerima;

                if ($jumlahInput > $sisaBolehDiterima) {
                    // [PERBAIKAN] Pesan error lebih deskriptif dengan nama barang.
                    $namaBarang = $detailPengadaan->barang->nama ?? "ID {$idBarang}";
                    throw new \Exception("Untuk barang '{$namaBarang}', jumlah diterima ({$jumlahInput}) melebihi sisa pesanan ({$sisaBolehDiterima}).");
                }
                // -----------------------------

                if ($jumlahInput <= 0) continue; // Pindahkan 'continue' ke sini. Hanya proses yang jumlahnya > 0.
                
                $totalDiterimaInput += $jumlahInput;

                // Simpan Detail Penerimaan
                DetailPenerimaan::create([
                    'idpenerimaan' => $penerimaan->idpenerimaan,
                    'idbarang' => $idBarang,
                    'jumlah' => $jumlahInput, 
                    'harga_satuan' => $hargaSatuan,
                ]);

                // Update Kartu Stok
                // Ambil stok terakhir
                $lastStock = DB::table('kartu_stok')
                    ->where('idbarang', $idBarang)
                    ->orderBy('created_at', 'desc')
                    ->orderBy('idkartu_stok', 'desc')
                    ->value('stock') ?? 0;

                DB::table('kartu_stok')->insert([
                    'jenis_transaksi' => 'P', // <--- UPDATE: 'P' sesuai data dummy SQL Anda
                    'masuk' => $jumlahInput,
                    'keluar' => 0,
                    'stock' => $lastStock + $jumlahInput,
                    'created_at' => now(),
                    'idtransaksi' => $penerimaan->idpenerimaan,
                    'idbarang' => $idBarang,
                ]);
                
                // Update Harga Barang Master (Opsional: update harga beli terbaru)
                // DB::table('barang')->where('idbarang', $idBarang)->update(['harga' => $hargaSatuan]);
            }

            if ($totalDiterimaInput == 0) {
                throw new \Exception("Tidak ada barang yang diterima. Isi minimal satu.");
            }

            // 3. Cek Status Pengadaan (Partial vs Full)
            $totalDipesanAll = $pengadaan->details->sum('jumlah');
            $totalDiterimaAll = DetailPenerimaan::whereHas('penerimaan', function ($query) use ($pengadaan) {
                $query->where('idpengadaan', $pengadaan->idpengadaan);
            })->sum('jumlah');

            if ($totalDiterimaAll >= $totalDipesanAll) {
                $pengadaan->update(['status' => 'S']); // Close PO jika semua barang diterima
            }

            DB::commit();

            return redirect()->route('admin.penerimaan.show', $penerimaan->idpenerimaan)
                ->with('success', 'Penerimaan berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $penerimaan = Penerimaan::with([
            'pengadaan.vendor', 
            'user', 
            'details.barang.satuan'
        ])->findOrFail($id);

        return view('admin.penerimaan.show', compact('penerimaan'));
    }

    // ... function getPengadaanDetails tetap sama seperti sebelumnya ...
    public function getPengadaanDetails($id)
    {
        try {
            $pengadaan = Pengadaan::with(['details.barang.satuan'])->findOrFail($id);

            $historyPenerimaan = DetailPenerimaan::whereHas('penerimaan', function ($query) use ($id) {
                $query->where('idpengadaan', $id);
            })
            ->select('idbarang', DB::raw('SUM(jumlah) as total_diterima'))
            ->groupBy('idbarang')
            ->pluck('total_diterima', 'idbarang');

            $details = $pengadaan->details->map(function ($detail) use ($historyPenerimaan) {
                $sudahDiterima = $historyPenerimaan[$detail->idbarang] ?? 0;
                return [
                    'idbarang' => $detail->idbarang,
                    'barang' => $detail->barang,
                    'jumlah' => $detail->jumlah,
                    'jumlah_diterima' => $sudahDiterima,
                    'sisa' => max(0, $detail->jumlah - $sudahDiterima)
                ];
            });

            return response()->json(['status' => 'success', 'details' => $details]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
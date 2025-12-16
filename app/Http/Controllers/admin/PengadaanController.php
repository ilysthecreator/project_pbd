<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pengadaan;
use App\Models\DetailPengadaan;
use App\Models\Vendor;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // Gunakan ini jika Anda punya sistem login

class PengadaanController extends Controller
{
    /**
     * Menampilkan daftar pengadaan
     * [UPDATE] Menggunakan Eloquent dengan eager loading & withCount
     */
    public function index()
    {
        $pengadaan = Pengadaan::with('vendor', 'user')
                        ->withCount('details as jumlah_item') // Otomatis menghitung relasi
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        // Accessor 'status_text' dari model akan otomatis bekerja
        return view('admin.pengadaan.index', compact('pengadaan'));
    }

    /**
     * Menampilkan form create
     * [UPDATE] Menggunakan Eloquent Model
     */
    public function create()
    {
        $vendors = Vendor::where('status', '1')->orderBy('nama_vendor')->get();
        
        // Asumsi: Model Barang Anda memiliki relasi 'satuan'
        $barang = Barang::with('satuan')
                    ->where('status', 1) 
                    ->orderBy('nama')
                    ->get()
                    // Map nama_satuan agar view tidak error
                    ->map(function($b) {
                        $b->nama_satuan = $b->satuan->nama_satuan ?? 'N/A';
                        return $b;
                    });
        
        return view('admin.pengadaan.create', compact('vendors', 'barang'));
    }

    /**
     * Menyimpan data pengadaan
     * [UPDATE] Menggunakan Eloquent Create & Relasi
     */
    public function store(Request $request)
    {
        $request->validate([
            'idvendor' => 'required|exists:vendor,idvendor',
            'idbarang' => 'required|array|min:1',
            'idbarang.*' => 'required|exists:barang,idbarang',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|integer|min:1',
            'harga_satuan' => 'required|array|min:1',
            'harga_satuan.*' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        
        try {
            // Hitung subtotal
            $subtotal = 0;
            $detailsData = [];
            foreach ($request->idbarang as $key => $idbarang) {
                $jumlah = $request->jumlah[$key];
                $harga = $request->harga_satuan[$key];
                $sub_total_item = $jumlah * $harga;
                $subtotal += $sub_total_item;
                
                $detailsData[] = [
                    'idbarang' => $idbarang,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'sub_total' => $sub_total_item,
                ];
            }
            
            $ppn = $subtotal * 0.1; // PPN 10%
            $total = $subtotal + $ppn;
            
            // 1. Insert pengadaan menggunakan Model
            $pengadaan = Pengadaan::create([
                'iduser' => 1, // [PERBAIKI] Ganti dengan Auth::id() jika sudah ada login
                'idvendor' => $request->idvendor,
                'subtotal_nilai' => $subtotal,
                'ppn' => $ppn,
                'total_nilai' => $total,
                'status' => 'P',
                // created_at dan timestamp dihandle otomatis oleh Eloquent
            ]);
            
            // 2. Insert detail pengadaan menggunakan relasi
            $pengadaan->details()->createMany($detailsData);
            
            DB::commit();
            
            // [PERBAIKAN] Mengarahkan ke route 'admin.pengadaan.index' yang benar
            return redirect()->route('admin.pengadaan.index')
                ->with('success', 'Data pengadaan berhasil ditambahkan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan detail pengadaan
     * [UPDATE] Menggunakan Eloquent
     */
    public function show($id)
    {
        $pengadaan = Pengadaan::with('vendor', 'user')->findOrFail($id);
        
        // Ambil detail dan relasi ke barang + satuan
        $details = DetailPengadaan::with('barang.satuan')
                    ->where('idpengadaan', $id)
                    ->get()
                    // Map nama agar view tidak error
                    ->map(function($d) {
                        $d->nama_barang = $d->barang->nama ?? 'N/A';
                        $d->nama_satuan = $d->barang->satuan->nama_satuan ?? 'N/A';
                        return $d;
                    });
        
        return view('admin.pengadaan.show', compact('pengadaan', 'details'));
    }

    /**
     * Menampilkan form edit
     * [UPDATE] Menggunakan Eloquent dan helper Model
     */
    public function edit($id)
    {
        $pengadaan = Pengadaan::findOrFail($id);
        
        // Menggunakan helper 'canBeEdited' dari Model
        if (!$pengadaan->canBeEdited()) {
            // [PERBAIKAN] Mengarahkan ke route 'admin.pengadaan.index' yang benar
            return redirect()->route('admin.pengadaan.index')
                ->with('error', 'Pengadaan dengan status ' . $pengadaan->status_text . ' tidak dapat diedit');
        }
        
        $vendors = Vendor::where('status', '1')->orderBy('nama_vendor')->get();
        $barang = Barang::with('satuan')
                    ->where('status', 1)
                    ->orderBy('nama')
                    ->get()
                    ->map(function($b) {
                        $b->nama_satuan = $b->satuan->nama_satuan ?? 'N/A';
                        return $b;
                    });
        
        // Ambil details dari relasi
        $details = $pengadaan->details; 
        
        return view('admin.pengadaan.edit', compact('pengadaan', 'vendors', 'barang', 'details'));
    }

    /**
     * Update data pengadaan
     * [UPDATE] Menggunakan Eloquent Update
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'idvendor' => 'required|integer',
            'idbarang' => 'required|array|min:1',
            'jumlah' => 'required|array|min:1',
            'harga_satuan' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        
        try {
            $pengadaan = Pengadaan::findOrFail($id);
            
            if (!$pengadaan->canBeEdited()) {
                return redirect()->route('admin.pengadaan.index')->with('error', 'Pengadaan ini tidak bisa diedit lagi.');
            }

            // Hitung ulang subtotal
            $subtotal = 0;
            $detailsData = [];
            foreach ($request->idbarang as $key => $idbarang) {
                $jumlah = $request->jumlah[$key];
                $harga = $request->harga_satuan[$key];
                $sub_total_item = $jumlah * $harga;
                $subtotal += $sub_total_item;
                
                $detailsData[] = [
                    'idbarang' => $idbarang,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'sub_total' => $sub_total_item,
                ];
            }
            
            $ppn = $subtotal * 0.1;
            $total = $subtotal + $ppn;
            
            // 1. Update pengadaan
            $pengadaan->update([
                'idvendor' => $request->idvendor,
                'subtotal_nilai' => $subtotal,
                'ppn' => $ppn,
                'total_nilai' => $total,
                // timestamp diupdate otomatis oleh Eloquent
            ]);
            
            // 2. Hapus detail lama
            $pengadaan->details()->delete();
            
            // 3. Insert detail baru
            $pengadaan->details()->createMany($detailsData);
            
            DB::commit();
            
            // [PERBAIKAN] Mengarahkan ke route 'admin.pengadaan.index' yang benar
            return redirect()->route('admin.pengadaan.index')
                ->with('success', 'Data pengadaan berhasil diupdate');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus pengadaan
     * [UPDATE] Menggunakan Eloquent Delete
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $pengadaan = Pengadaan::findOrFail($id);
            
            // Logika pengecekan penerimaan (sudah benar)
            $penerimaan = DB::select("SELECT * FROM penerimaan WHERE idpengadaan = ?", [$id]);
            if (!empty($penerimaan)) {
                return redirect()->route('admin.pengadaan.index')
                    ->with('error', 'Pengadaan tidak dapat dihapus karena sudah ada penerimaan');
            }
            
            // Hapus detail terlebih dahulu
            $pengadaan->details()->delete();
            
            // Hapus pengadaan
            $pengadaan->delete();
            
            DB::commit();
            
            // [PERBAIKAN] Mengarahkan ke route 'admin.pengadaan.index' yang benar
            return redirect()->route('admin.pengadaan.index')
                ->with('success', 'Data pengadaan berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update status pengadaan
     * [UPDATE] Menggunakan helper Model
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:P,S,C'
        ]);

        $pengadaan = Pengadaan::findOrFail($id);
        
        // Menggunakan method 'updateStatus' dari Model
        $pengadaan->updateStatus($request->status);

        // [PERBAIKAN] Mengarahkan ke route 'admin.pengadaan.index' yang benar
        return redirect()->route('admin.pengadaan.index')
            ->with('success', 'Status pengadaan berhasil diupdate');
    }
}
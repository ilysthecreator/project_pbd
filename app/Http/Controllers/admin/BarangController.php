<?php

namespace App\Http\Controllers\Admin;

use App\Models\Barang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
 {
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $query = Barang::query()->with('satuan');

        if ($status === 'aktif') {
            $query->where('status', 1);
        } elseif ($status === 'tidak-aktif') {
            $query->where('status', 0);
        }
        
        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $barang = $query
            ->orderBy('idbarang', 'desc')
            ->paginate(10);
        
        return view('admin.barang.index', compact('barang', 'status', 'search'));
    }

    public function create()
    {
        $satuan = DB::table('satuan')
            ->where('status', 1)
            ->get();
        
        return view('admin.barang.create', compact('satuan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:45',
            'jenis' => 'required|in:B,K',
            'harga' => 'required|integer|min:0',
            'idsatuan' => 'required|exists:satuan,idsatuan',
            'status' => 'required|in:0,1'
        ]);

        try {
            DB::table('barang')->insert([
                'nama' => $validated['nama'],
                'jenis' => $validated['jenis'],
                'harga' => $validated['harga'],
                'idsatuan' => $validated['idsatuan'],
                'status' => $validated['status']
            ]);

            return redirect()
                ->route('admin.barang.index')
                ->with('success', 'Barang berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan barang: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $barang = DB::table('barang')
            ->where('idbarang', $id)
            ->first();

        if (!$barang) {
            return redirect()
                ->route('admin.barang.index')
                ->with('error', 'Barang tidak ditemukan!');
        }

        $satuan = DB::table('satuan')
            ->where('status', 1)
            ->get();
        
        return view('admin.barang.edit', compact('barang', 'satuan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:45',
            'jenis' => 'required|in:B,K',
            'harga' => 'required|integer|min:0',
            'idsatuan' => 'required|exists:satuan,idsatuan',
            'status' => 'required|in:0,1'
        ]);

        try {
            DB::table('barang')
                ->where('idbarang', $id)
                ->update([
                    'nama' => $validated['nama'],
                    'jenis' => $validated['jenis'],
                    'harga' => $validated['harga'],
                    'idsatuan' => $validated['idsatuan'],
                    'status' => $validated['status']
                ]);

            return redirect()
                ->route('admin.barang.index')
                ->with('success', 'Barang berhasil diupdate!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate barang: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Check if barang is used in other tables
            $usedInPenjualan = DB::table('detail_penjualan')
                ->where('idbarang', $id)
                ->exists();
                
            $usedInPengadaan = DB::table('detail_pengadaan')
                ->where('idbarang', $id)
                ->exists();

            if ($usedInPenjualan || $usedInPengadaan) {
                return redirect()
                    ->back()
                    ->with('error', 'Barang tidak dapat dihapus karena sudah digunakan dalam transaksi!');
            }

            DB::table('barang')
                ->where('idbarang', $id)
                ->delete();

            return redirect()
                ->route('admin.barang.index')
                ->with('success', 'Barang berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }
}

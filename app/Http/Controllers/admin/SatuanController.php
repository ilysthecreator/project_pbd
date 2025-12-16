<?php

namespace App\Http\Controllers\Admin;

use App\Models\Satuan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $query = Satuan::query();

        if ($status === 'aktif') {
            $query->has('barang'); // Satuan is "active" if it's linked to at least one barang
        } elseif ($status === 'tidak-aktif') {
            $query->doesntHave('barang'); // Satuan is "inactive" if it's not linked to any barang
        }

        if ($search) {
            $query->where('nama_satuan', 'like', '%' . $search . '%');
        }

        $satuan = $query->withCount('barang as jumlah_barang')
            ->orderBy('idsatuan', 'desc')
            ->paginate(10);
        
        return view('admin.satuan.index', compact('satuan', 'status', 'search'));
    }

    public function create()
    {
        return view('admin.satuan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_satuan' => 'required|string|max:45|unique:satuan,nama_satuan',
            'status' => 'required|in:0,1'
        ]);

        try {
            // Menggunakan Eloquent untuk konsistensi
            Satuan::create($validated);

            return redirect()
                ->route('admin.satuan.index')
                ->with('success', 'Satuan berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan satuan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $satuan = Satuan::find($id);

        if (!$satuan) {
            return redirect()
                ->route('admin.satuan.index')
                ->with('error', 'Satuan tidak ditemukan!');
        }
        
        return view('admin.satuan.edit', compact('satuan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_satuan' => 'required|string|max:45|unique:satuan,nama_satuan,' . $id . ',idsatuan',
            'status' => 'required|in:0,1'
        ]);

        try {
            $satuan = Satuan::findOrFail($id);
            $satuan->update($validated);

            return redirect()
                ->route('admin.satuan.index')
                ->with('success', 'Satuan berhasil diupdate!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate satuan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Check if satuan is used by barang
            $countBarang = DB::table('barang')
                ->where('idsatuan', $id)
                ->count();

            if ($countBarang > 0) {
                return redirect()
                    ->back()
                    ->with('error', "Satuan tidak dapat dihapus karena digunakan oleh {$countBarang} barang!");
            }

            DB::table('satuan')
                ->where('idsatuan', $id)
                ->delete();

            return redirect()
                ->route('admin.satuan.index')
                ->with('success', 'Satuan berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus satuan: ' . $e->getMessage());
        }
    }
}

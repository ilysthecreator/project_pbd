<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = Vendor::query();

        if ($status === 'aktif') {
            $query->where('status', '1');
        } elseif ($status === 'tidak-aktif') {
            $query->where('status', '0');
        }

        $vendor = $query
            ->orderBy('idvendor', 'desc')
            ->paginate(10);
        
        // Append status to pagination links
        $vendor->appends(['status' => $status]);
        
        return view('admin.vendor.index', compact('vendor', 'status'));
    }
    public function create()
    {
        return view('admin.vendor.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_vendor' => 'required|string|max:100',
            'badan_hukum' => 'required|in:B,U',
            'status' => 'required|in:0,1'
        ]);

        try {
            DB::table('vendor')->insert([
                'nama_vendor' => $validated['nama_vendor'],
                'badan_hukum' => $validated['badan_hukum'],
                'status' => $validated['status']
            ]);

            return redirect()
                ->route('admin.vendor.index')
                ->with('success', 'Vendor berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan vendor: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $vendor = DB::table('vendor')
            ->where('idvendor', $id)
            ->first();

        if (!$vendor) {
            return redirect()
                ->route('admin.vendor.index')
                ->with('error', 'Vendor tidak ditemukan!');
        }
        
        return view('admin.vendor.edit', compact('vendor'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_vendor' => 'required|string|max:100',
            'badan_hukum' => 'required|in:B,U',
            'status' => 'required|in:0,1'
        ]);

        try {
            DB::table('vendor')
                ->where('idvendor', $id)
                ->update([
                    'nama_vendor' => $validated['nama_vendor'],
                    'badan_hukum' => $validated['badan_hukum'],
                    'status' => $validated['status']
                ]);

            return redirect()
                ->route('admin.vendor.index')
                ->with('success', 'Vendor berhasil diupdate!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate vendor: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Check if vendor is used
            $usedInPengadaan = DB::table('pengadaan')
                ->where('idvendor', $id)
                ->exists();

            if ($usedInPengadaan) {
                return redirect()
                    ->back()
                    ->with('error', 'Vendor tidak dapat dihapus karena sudah digunakan dalam pengadaan!');
            }

            DB::table('vendor')
                ->where('idvendor', $id)
                ->delete();

            return redirect()
                ->route('admin.vendor.index')
                ->with('success', 'Vendor berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus vendor: ' . $e->getMessage());
        }
    }
}
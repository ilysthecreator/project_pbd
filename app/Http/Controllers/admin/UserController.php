<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('iduser', 'asc')->paginate(10);

        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:45|unique:user,username',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'username.max' => 'Username maksimal 45 karakter',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);

            return redirect()
                ->route('admin.user.index')
                ->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()
                ->route('admin.user.index')
                ->with('error', 'User tidak ditemukan!');
        }

        // Menggunakan accessor dari model
        $jumlahPengadaan = $user->total_pengadaan;

        return view('admin.user.show', compact('user', 'jumlahPengadaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()
                ->route('admin.user.index')
                ->with('error', 'User tidak ditemukan!');
        }

        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'username' => 'required|string|max:45|unique:user,username,' . $id . ',iduser',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'username.max' => 'Username maksimal 45 karakter',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            $user = User::findOrFail($id);

            $data = [
                'username' => $request->username,
            ];

            // Hanya update password jika diisi
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()
                ->route('admin.user.index')
                ->with('success', 'User berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);

            // Menggunakan method canBeDeleted dari model
            if (!$user->canBeDeleted()) {
                if ($user->is_super_admin) {
                    return redirect()
                        ->route('admin.user.index')
                        ->with('error', 'User Super Admin tidak dapat dihapus!');
                }

                return redirect()
                    ->route('admin.user.index')
                    ->with('error', 'User tidak dapat dihapus karena memiliki ' . $user->total_pengadaan . ' pengadaan!');
            }

            $user->delete();

            return redirect()
                ->route('admin.user.index')
                ->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.user.index')
                ->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}
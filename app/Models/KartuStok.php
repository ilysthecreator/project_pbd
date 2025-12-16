<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuStok extends Model
{
    use HasFactory;

    protected $table = 'kartu_stok';
    protected $primaryKey = 'idkartu_stok';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'idbarang',
        'idtransaksi',
        'jenis_transaksi',
        'masuk',
        'keluar',
        'stock',
        'created_at', // Tambahkan ini agar bisa diisi manual saat insert
        'iduser'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang', 'idbarang');
    }

    public function getJenisTransaksiTextAttribute()
    {
        // Update kode 'B' menjadi 'P' sesuai database project_pbd.sql
        return match ($this->jenis_transaksi) {
            'P' => 'Penerimaan', // <-- GANTI DARI 'B' KE 'P'
            'J' => 'Penjualan',
            'R' => 'Retur',
            default => 'Lainnya',
        };
    }
}
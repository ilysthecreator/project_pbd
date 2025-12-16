<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $primaryKey = 'idpenjualan';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null; // Abaikan kolom updated_at

    protected $fillable = [
        'iduser',
        'nama_pelanggan',
        'subtotal_nilai',
        'ppn',
        'total_nilai',
        'total_bayar',
        'kembalian',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    public function details()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_idpenjualan', 'idpenjualan');
    }

    public function retur()
    {
        return $this->hasMany(ReturPenjualan::class, 'idpenjualan', 'idpenjualan');
    }
}
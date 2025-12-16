<?php

namespace App\Models; // <-- PASTIKAN INI BENAR

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';
    protected $primaryKey = 'iddetail_penjualan';
    public $timestamps = false;

    protected $fillable = [
        'penjualan_idpenjualan',
        'idbarang',
        'harga_satuan',
        'jumlah',
        'subtotal',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_idpenjualan', 'idpenjualan');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang', 'idbarang');
    }
}
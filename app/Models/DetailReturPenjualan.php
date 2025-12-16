<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailReturPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_retur_penjualan';
    protected $primaryKey = 'iddetail_retur';
    public $timestamps = false;

    protected $fillable = [
        'idretur',
        'idbarang',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function retur()
    {
        return $this->belongsTo(ReturPenjualan::class, 'idretur', 'idretur');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang', 'idbarang');
    }
}
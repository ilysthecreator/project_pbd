<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPengadaan extends Model
{
    protected $table = 'detail_pengadaan';
    protected $primaryKey = 'iddetail_pengadaan';
    public $timestamps = false;

    protected $fillable = [
        'harga_satuan',
        'jumlah',
        'sub_total',
        'idbarang',
        'idpengadaan'
    ];

    public function pengadaan()
    {
        return $this->belongsTo(Pengadaan::class, 'idpengadaan', 'idpengadaan');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang', 'idbarang');
    }
}

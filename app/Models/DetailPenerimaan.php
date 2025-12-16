<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenerimaan extends Model
{
    use HasFactory;

    protected $table = 'detail_penerimaan';
    protected $primaryKey = 'iddetail_penerimaan'; // Sesuai DDL
    public $timestamps = false; // Sesuai DDL (tidak ada created_at/updated_at)

    protected $fillable = [
        'idpenerimaan',
        'idbarang',
        'jumlah',
        'harga_satuan',
    ];

    public function penerimaan()
    {
        return $this->belongsTo(Penerimaan::class, 'idpenerimaan', 'idpenerimaan');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang', 'idbarang');
    }
}
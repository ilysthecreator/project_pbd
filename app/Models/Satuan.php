<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;

    protected $table = 'satuan';
    protected $primaryKey = 'idsatuan';
    public $timestamps = false;

    protected $fillable = [
        'nama_satuan',
    ];

    protected $casts = [
    ];
    public function barang()
    {
        return $this->hasMany(Barang::class, 'idsatuan', 'idsatuan');
    }
    public function getJumlahBarangAttribute()
    {
        return $this->barang()->count();
    }
    public function getStatusTextAttribute()
    {
        return $this->jumlah_barang > 0 ? 'Digunakan' : 'Tidak Digunakan';
    }

    public function getStatusAttribute()
    {
        return $this->jumlah_barang > 0 ? 1 : 0;
    }

    public function scopeAktif($query)
    {
        return $query->has('barang');
    }
}
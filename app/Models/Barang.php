<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'idbarang';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'jenis',
        'harga',
        'idsatuan',
        'status'
    ];

    protected $casts = [
        'harga' => 'integer',
        'idsatuan' => 'integer',
        'status' => 'integer'
    ];

    // Relationships
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'idsatuan', 'idsatuan');
    }

    public function kartuStok()
    {
        return $this->hasMany(KartuStok::class, 'idbarang', 'idbarang');
    }

    public function detailPengadaan()
    {
        return $this->hasMany(DetailPengadaan::class, 'idbarang', 'idbarang');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'idbarang', 'idbarang');
    }

    public function detailPenerimaan()
    {
        return $this->hasMany(DetailPenerimaan::class, 'idbarang', 'idbarang');
    }

    public function penerimaan()
    {
        return $this->hasManyThrough(Penerimaan::class, DetailPenerimaan::class, 'idbarang', 'idpenerimaan', 'idbarang', 'idpenerimaan');
    }

    // Accessor untuk jenis lengkap
    public function getJenisLengkapAttribute()
    {
        return $this->jenis === 'B' ? 'Barang' : 'Kimia';
    }

    // Accessor untuk status text
    public function getStatusTextAttribute()
    {
        return $this->status === 1 ? 'Aktif' : 'Tidak Aktif';
    }

    // Scope untuk barang aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 1);
    }

    // Get stok saat ini menggunakan function
    public function getStokAttribute()
    {
        // Menghitung stok langsung dari tabel kartu_stok
        return \DB::table('kartu_stok')
                    ->where('idbarang', $this->idbarang)
                    ->sum(\DB::raw('masuk - keluar'));
    }

    // Format harga
    public function getHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}

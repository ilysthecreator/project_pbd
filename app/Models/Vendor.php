<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendor';
    protected $primaryKey = 'idvendor';
    public $timestamps = false;

    protected $fillable = [
        'nama_vendor',
        'badan_hukum',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];
    public function pengadaan()
    {
        return $this->hasMany(Pengadaan::class, 'idvendor', 'idvendor');
    }
    public function getBadanHukumTextAttribute()
    {
        return $this->badan_hukum === 'B' ? 'Badan Usaha' : 'Usaha Pribadi';
    }
    public function getStatusTextAttribute()
    {
        return $this->status === '1' ? 'Aktif' : 'Tidak Aktif';
    }
    public function scopeAktif($query)
    {
        return $query->where('status', '1');
    }
    public function getTotalPengadaanAttribute()
    {
        return $this->pengadaan()->sum('total_nilai');
    }
}

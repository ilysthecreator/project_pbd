<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturPenjualan extends Model
{
    use HasFactory;

    protected $table = 'retur_penjualan';
    protected $primaryKey = 'idretur';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'idpenjualan',
        'iduser',
        'alasan',
        'total_nilai_retur',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'idpenjualan', 'idpenjualan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    public function details()
    {
        return $this->hasMany(DetailReturPenjualan::class, 'idretur', 'idretur');
    }
}
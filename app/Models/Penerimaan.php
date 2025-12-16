<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    use HasFactory;

    protected $table = 'penerimaan';
    protected $primaryKey = 'idpenerimaan'; // Sesuai DDL

    // Sesuai DDL: hanya ada created_at, tidak ada updated_at
    public $timestamps = true; 
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null; 

    protected $fillable = [
        'idpengadaan',
        'iduser',
        'status', // Default 'S' di DDL
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function pengadaan()
    {
        return $this->belongsTo(Pengadaan::class, 'idpengadaan', 'idpengadaan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    public function details()
    {
        return $this->hasMany(DetailPenerimaan::class, 'idpenerimaan', 'idpenerimaan');
    }
}
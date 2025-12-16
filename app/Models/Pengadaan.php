<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    use HasFactory;
    protected $table = 'pengadaan';
    protected $primaryKey = 'idpengadaan';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'timestamp';
    protected $fillable = [
        'iduser',
        'idvendor',
        'subtotal_nilai',
        'ppn',
        'total_nilai',
        'status',
    ];

    protected $casts = [
        'subtotal_nilai' => 'decimal:2',
        'ppn' => 'decimal:2',
        'total_nilai' => 'decimal:2',
        'created_at' => 'datetime',
        'timestamp' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'idvendor', 'idvendor');
    }
    public function details()
    {
        return $this->hasMany(DetailPengadaan::class, 'idpengadaan', 'idpengadaan');
    }
    public function penerimaan()
    {
        return $this->hasMany(Penerimaan::class, 'idpengadaan', 'idpengadaan');
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'S' => 'Selesai',
            'P' => 'Pending',
            'C' => 'Cancel',
            default => 'Unknown',
        };
    }
    public function getJumlahItemAttribute()
    {
        return $this->details()->count();
    }
    public function scopePending($query)
    {
        return $query->where('status', 'P');
    }
    public function scopeSelesai($query)
    {
        return $query->where('status', 'S');
    }
    public function scopeCancel($query)
    {
        return $query->where('status', 'C');
    }
    public function canBeEdited()
    {
        return $this->status === 'P';
    }
    public function canBeDeleted()
    {
        return $this->status === 'P';
    }
    public function updateStatus($status)
    {
        $this->status = $status;
        $this->timestamp = now();
        return $this->save();
    }
}
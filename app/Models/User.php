<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'iduser';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'idrole', // Ditambahkan agar bisa diisi saat create/update user
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function pengadaan()
    {
        return $this->hasMany(Pengadaan::class, 'iduser', 'iduser');
    }

    public function getRoleAttribute()
    {
        return $this->iduser == 1 ? 'Super Admin' : 'Admin';
    }

    public function getIsSuperAdminAttribute()
    {
        return $this->iduser == 1;
    }

    public function scopeActive($query)
    {
        return $query->where('iduser', '>', 0);
    }

    public function scopeRegularAdmin($query)
    {
        return $query->where('iduser', '!=', 1);
    }

    public function canBeDeleted()
    {
        if ($this->iduser == 1) {
            return false;
        }
        if ($this->pengadaan()->count() > 0) {
            return false;
        }

        return true;
    }

    public function getTotalPengadaanAttribute()
    {
        return $this->pengadaan()->count();
    }

    public function getTotalNilaiPengadaanAttribute()
    {
        return $this->pengadaan()->sum('total_nilai') ?? 0;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'tgl_pinjam',
        'tgl_kembali_plan',
        'status',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke DetailPinjam
    public function detailpinjams()
    {
        return $this->hasMany(DetailPinjam::class, 'peminjaman_id');
    }

    // Alias relasi untuk details
    public function details()
    {
        return $this->hasMany(DetailPinjam::class, 'peminjaman_id');
    }

    // Relasi ke Pengembalian
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id');
    }
}
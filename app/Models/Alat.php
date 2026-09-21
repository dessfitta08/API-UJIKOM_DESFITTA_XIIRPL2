<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    // Tambahkan baris ini agar Laravel membaca tabel 'alat'
    protected $table = 'alat';

    protected $guarded = ['id'];

    /**
     * Scope untuk menyaring alat yang stoknya tersedia dan kondisinya baik.
     */
    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0)
                     ->where('status_kondisi', 'Baik');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'nama_barang',
        'harga',
        'deskripsi',
        'foto',
        'video_review',
        'metode_serah_terima',
        'titik_lokasi',
        'status',
    ];

    // Tambahkan relasi ke User (Mahasiswa yang menjual)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tambahkan relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

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
        'moderation_status',
        'rejection_reason',
        'moderated_at',
    ];

    protected $casts = [
        'moderated_at' => 'datetime',
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

    // Scope untuk katalog mahasiswa (hanya barang yang sudah dikonfirmasi/approved)
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function photos()
    {
        return $this->hasMany(ProductPhoto::class)->orderBy('sort_order');
    }

    // Helper agar tetap kompatibel dengan data lama ($product->foto)
    public function getPrimaryPhotoUrlAttribute(): ?string
    {
        $firstPhoto = $this->relationLoaded('photos')
            ? $this->photos->first()
            : $this->photos()->first();

        if ($firstPhoto) {
            return asset('storage/' . $firstPhoto->path);
        }

        return $this->foto ? asset('storage/' . $this->foto) : null;
    }
}
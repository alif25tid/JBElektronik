<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke penjual
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Relasi ke kategori
            $table->string('nama_barang');
            $table->integer('harga');
            $table->text('deskripsi'); // Detail kondisi fisik & spesifikasi teknis
            $table->string('foto'); // Path file foto
            $table->string('video_review'); // Path file video pembuktian fungsi
            $table->enum('metode_serah_terima', ['COD Kampus', 'Ambil di Kos']);
            $table->string('titik_lokasi'); // Contoh: Gazebo Utama / Gedung Serba Guna / Kosan
            $table->enum('status', ['tersedia', 'terjual'])->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

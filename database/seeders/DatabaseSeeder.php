<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin Utama (menggunakan email pcr.ac.id)
        User::create([
            'name' => 'Administrator Kampus',
            'email' => 'admin@pcr.ac.id',
            'password' => Hash::make('passwordadmin123'),
            'role' => 'admin',
            'no_whatsapp' => '6281234567890',
        ]);

        // 2. Buat Contoh Akun Mahasiswa
        User::create([
            'name' => 'Alif Al Fikhri',
            'email' => 'alif@pcr.ac.id',
            'password' => Hash::make('passwordmahasiswa'),
            'role' => 'mahasiswa',
            'no_whatsapp' => '6289876543210',
        ]);

        // 3. Buat Kategori Barang Elektronik & Kampus
        $categories = [
            'Komponen Praktikum (Router/Switch)',
            'Kebutuhan Kuliah (Laptop/Aksesoris)',
            'Peralatan Kos (Kipas/Elektronik Kecil)'
        ];

        foreach ($categories as $cat) {
            Category::create(['nama_kategori' => $cat]);
        }
    }
}
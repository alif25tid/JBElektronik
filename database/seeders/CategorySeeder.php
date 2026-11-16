<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Laptop & Komputer'],
            ['name' => 'Handphone & Tablet'],
            ['name' => 'Aksesoris & Peripheral'],
            ['name' => 'Audio & Kamera'],
            ['name' => 'Komponen Elektronik'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

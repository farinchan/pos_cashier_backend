<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Nette\Utils\Random;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'fajri',
            'username' => 'fajri_chan',
            'email' => 'fajri@gariskode.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Bumbu Dapur',
            'description' => 'Bumbu dapur adalah bahan-bahan yang digunakan untuk menambah rasa pada masakan.',
        ]);

        Category::create([
            'name' => 'snack',
            'description' => 'Makan ringan yang biasanya disantap di antara waktu makan.',
        ]);

        Category::create([
            'name' => 'Minuman',
            'description' => 'Minuman segar.'
        ]);

        Category::create([
            'name' => 'Es Krim',
            'description' => 'Es krim adalah makanan beku yang terbuat dari susu dan gula.',
        ]);

        Category::create([
            'name' => 'Mie Instan',
            'description' => 'Mie instan adalah makanan instan yang terbuat dari mie.',
        ]);

        Category::create([
            'name' => 'Sembako',
            'description' => 'Sembako adalah kebutuhan pokok yang dibutuhkan oleh manusia.',
        ]);

        Category::create([
            'name' => 'Peralatan Mandi',
            'description' => 'Peralatan mandi adalah alat-alat yang digunakan untuk mandi.',
        ]); 

        Category::create([
            'name' => 'peralatan Sekolah',
            'description' => 'Peralatan sekolah adalah alat-alat yang digunakan untuk belajar di sekolah.',
        ]);

        Category::create([
            'name' => 'Peralatan Olahraga',
            'description' => 'Peralatan olahraga adalah alat-alat yang digunakan untuk berolahraga.',
        ]);

        Product::factory(500)->create()->each(function ($product) {
            $product->name = 'Product ID-' . $product->id . ' dengan kategori ' . $product->category->name;
            $product->save();
        });

    }
}

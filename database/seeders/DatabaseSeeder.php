<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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

        Product::create([
            'name' => 'Garam Dolphin 300gr',
            'description' => 'Garam dapur adalah garam yang digunakan untuk menambah rasa pada masakan.',
            'price' => 2000,
            'stock' => 100,
            'category_id' => 1,
            'barcode' => '8992718000011',
        ]);
        
        Product::create([
            'name' => 'Sasa Tepung Terigu 1kg',
            'description' => 'Tepung terigu adalah tepung yang digunakan untuk membuat kue.',
            'price' => 8000,
            'stock' => 100,
            'category_id' => 1,
            'barcode' => '8992718000022',
        ]);

        Product::create([
            'name' => 'Sasa Tepung Beras 1kg',
            'description' => 'Tepung beras adalah tepung yang digunakan untuk membuat kue.',
            'price' => 10000,
            'stock' => 100,
            'category_id' => 1,
            'barcode' => '8992718000033',
        ]);

        Product::create([
            'name' => 'Sasa Tepung Jagung 1kg',
            'description' => 'Tepung jagung adalah tepung yang digunakan untuk membuat kue.',
            'price' => 12000,
            'stock' => 100,
            'category_id' => 1,
            'barcode' => '8992718000044',
        ]);

        Product::create([
            'name' => 'Sasa Tepung Kanji 1kg',
            'description' => 'Tepung kanji adalah tepung yang digunakan untuk membuat kue.',
            'price' => 15000,
            'stock' => 100,
            'category_id' => 1,
            'barcode' => '8992718000055',
        ]);

        Product::create([
            'name' => 'Roma Sari Gandum 190gr',
            'description' => 'Biskuit roma adalah biskuit yang enak.',
            'price' => 5000,
            'stock' => 100,
            'category_id' => 2,
            'barcode' => '8992718000066',
        ]);

        Product::create([
            'name' => 'Roma Kelapa 300gr',
            'description' => 'Biskuit roma adalah biskuit yang enak.',
            'price' => 5000,
            'stock' => 100,
            'category_id' => 2,
            'barcode' => '8992718000077',
        ]);

        Product::create([
            'name' => 'Unibis See Hong Puff 100gr',
            'description' => 'Biskuit unibis adalah biskuit yang enak.',
            'price' => 5000,
            'stock' => 100,
            'category_id' => 2,
            'barcode' => '8992718000088',
        ]);

        Product::create([
            'name' => 'Golda Chapucino 100gr',
            'description' => 'minuman instan yang enak.',
            'price' => 5000,
            'stock' => 100,
            'category_id' => 3,
            'barcode' => '8992718000099',
        ]);


    }
}

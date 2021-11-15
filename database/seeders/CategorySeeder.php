<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['title' => 'jewels']);
        Category::create(['title' => 'laptops']);
        Category::create(['title' => 'phones']);
        Category::create(['title' => 'sofas']);
    }
}

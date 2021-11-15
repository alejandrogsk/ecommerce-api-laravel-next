<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name' => 'dior',
            'category_id'=> 1,
            'description' => 'Stephen Silver’s new catalogue features original photography created by Lorem Ipsum’s team. To completely overhaul the Stephen Silver look and feel, we developed a new style guide, produced new product photography, and designed a new catalog that clearly highlights the company’s high-end watches and jewelry pieces.',
            'price' => 200,
            'quantity' => 10,
            'img' => urlencode('/storage/jewels-1.jpg')
        ]);

        Product::create([
            'name' => 'ruby',
            'category_id'=> 1,
            'description' => 'Stephen Silver’s new catalogue features original photography created by Lorem Ipsum’s team. To completely overhaul the Stephen Silver look and feel, we developed a new style guide, produced new product photography, and designed a new catalog that clearly highlights the company’s high-end watches and jewelry pieces.',
            'price' => 200,
            'quantity' => 10,
            'img' => urlencode('/storage/jewels-2.jpg')
        ]);

        Product::create([
            'name' => 'jade',
            'category_id'=> 1,
            'description' => 'Stephen Silver’s new catalogue features original photography created by Lorem Ipsum’s team. To completely overhaul the Stephen Silver look and feel, we developed a new style guide, produced new product photography, and designed a new catalog that clearly highlights the company’s high-end watches and jewelry pieces.',
            'price' => 200,
            'quantity' => 10,
            'img' => urlencode('/storage/jewels-3.jpg')
        ]);

        Product::create([
            'name' => 'atlantis',
            'category_id'=> 1,
            'description' => 'Stephen Silver’s new catalogue features original photography created by Lorem Ipsum’s team. To completely overhaul the Stephen Silver look and feel, we developed a new style guide, produced new product photography, and designed a new catalog that clearly highlights the company’s high-end watches and jewelry pieces.',
            'price' => 200,
            'quantity' => 10,
            'img' => urlencode('/storage/jewels-4.jpg')
        ]);

        Product::create([
            'name' => 'prince',
            'category_id'=> 1,
            'description' => 'Stephen Silver’s new catalogue features original photography created by Lorem Ipsum’s team. To completely overhaul the Stephen Silver look and feel, we developed a new style guide, produced new product photography, and designed a new catalog that clearly highlights the company’s high-end watches and jewelry pieces.',
            'price' => 200,
            'quantity' => 10,
            'img' => urlencode('/storage/jewels-5.jpg')
        ]);

        Product::create([
            'name' => 'gold',
            'category_id'=> 1,
            'description' => 'Stephen Silver’s new catalogue features original photography created by Lorem Ipsum’s team. To completely overhaul the Stephen Silver look and feel, we developed a new style guide, produced new product photography, and designed a new catalog that clearly highlights the company’s high-end watches and jewelry pieces.',
            'price' => 200,
            'quantity' => 10,
            'img' => urlencode('/storage/jewels-6.jpg')
        ]);

        Product::create([
            'name' => 'absolute',
            'category_id'=> 1,
            'description' => 'Stephen Silver’s new catalogue features original photography created by Lorem Ipsum’s team. To completely overhaul the Stephen Silver look and feel, we developed a new style guide, produced new product photography, and designed a new catalog that clearly highlights the company’s high-end watches and jewelry pieces.',
            'price' => 200,
            'quantity' => 10,
            'img' => urlencode('/storage/jewels-7.jpg')
        ]);


        //Laptops
        Product::create([
            'name' => 'hp',
            'category_id'=> 2,
            'description' => 'Many desktop publishing packages and web page editors now use lorem ipsum as their default model text, and a search for lorem ipsum will uncover many web sites still in their infancy.',
            'price' => 500,
            'quantity' => 30,
            'img' => urlencode('/storage/laptop-1.jpg')
        ]);

        Product::create([
            'name' => 'samsung',
            'category_id'=> 3,
            'description' => 'Many desktop publishing packages and web page editors now use lorem ipsum as their default model text, and a search for lorem ipsum will uncover many web sites still in their infancy.',
            'price' => 200.50,
            'quantity' => 100,
            'img' => urlencode('/storage/phone-1.jpg')
        ]);

        Product::create([
            'name' => 'kabal',
            'category_id'=> 4,
            'description' => 'Many desktop publishing packages and web page editors now use lorem ipsum as their default model text, and a search for lorem ipsum will uncover many web sites still in their infancy.',
            'price' => 600.50,
            'quantity' => 50,
            'img' => urlencode('/storage/sofa-1.jpg')
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::factory()->count(30)->create([
            'open' => 1,
            'custom' => 0,
            'product_id' => null
        ]);

        foreach($products as $product){
            $product->categories()->attach(1);
        }
    }
}

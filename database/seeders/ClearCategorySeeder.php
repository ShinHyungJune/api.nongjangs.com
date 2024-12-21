<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ClearCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::cursor();

        foreach($products as $product){
            if($product->category_id)
                $product->categories()->sync([$product->category_id]);
        }
    }
}

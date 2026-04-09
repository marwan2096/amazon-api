<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
       $electronics = Category::where('name', 'Electronics')->firstOrFail()->id;
        $clothing    = Category::where('name', 'Clothing')->first()->id;
        $phones      = Category::where('name', 'Phones')->first()->id;
        $laptops     = Category::where('name', 'Laptops')->first()->id;
        $headphones  = Category::where('name', 'Headphones')->first()->id;
        $men         = Category::where('name', 'Men')->first()->id;
        $women       = Category::where('name', 'Women')->first()->id;

        $products = [
            [
                'name'        => 'iPhone 15 Pro',
                'description' => 'Apple iPhone 15 Pro 256GB',
                'price'       => 999.99,
                'stock'       => 50,
                'sku'         => 'IPH-15-PRO',
                'is_active'   => true,
                'categories'  => [$electronics, $phones], // parent + child
            ],
            [
                'name'        => 'Samsung Galaxy S24',
                'description' => 'Samsung Galaxy S24 128GB',
                'price'       => 799.99,
                'stock'       => 30,
                'sku'         => 'SAM-S24',
                'is_active'   => true,
                'categories'  => [$electronics, $phones],
            ],
            [
                'name'        => 'Sony WH-1000XM5',
                'description' => 'Noise Cancelling Headphones',
                'price'       => 349.99,
                'stock'       => 100,
                'sku'         => 'SNY-WH1000',
                'is_active'   => true,
                'categories'  => [$electronics, $headphones],
            ],
            [
                'name'        => 'MacBook Pro 14',
                'description' => 'Apple MacBook Pro 14-inch M3',
                'price'       => 1999.99,
                'stock'       => 20,
                'sku'         => 'MBP-14-M3',
                'is_active'   => true,
                'categories'  => [$electronics, $laptops],
            ],
            [
                'name'        => 'Logitech MX Master 3',
                'description' => 'Advanced Wireless Mouse',
                'price'       => 99.99,
                'stock'       => 200,
                'sku'         => 'LOG-MX3',
                'is_active'   => false,
                'categories'  => [$electronics, $laptops],
            ],
            [
                'name'        => 'Nike Running Shirt',
                'description' => 'Lightweight running shirt for men',
                'price'       => 39.99,
                'stock'       => 150,
                'sku'         => 'NKE-RUN-SH',
                'is_active'   => true,
                'categories'  => [$clothing, $men],
            ],
            [
                'name'        => 'Zara Summer Dress',
                'description' => 'Floral summer dress for women',
                'price'       => 59.99,
                'stock'       => 80,
                'sku'         => 'ZRA-SUM-DR',
                'is_active'   => true,
                'categories'  => [$clothing, $women],
            ],
        ];

        foreach ($products as $product) {
            $categories = $product['categories'];
            unset($product['categories']);

            $item = Product::create([
                ...$product,
                'slug' => Str::slug($product['name']),
            ]);

            $item->categories()->attach($categories); // attach multiple at once
        }
    }
}

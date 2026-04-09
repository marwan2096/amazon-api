<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Electronics',
                'description' => 'All electronic devices and gadgets',
                'is_active'   => true,
                'children'    => [
                    ['name' => 'Phones',     'description' => 'Smartphones and mobile phones'],
                    ['name' => 'Laptops',    'description' => 'Laptops and notebooks'],
                    ['name' => 'Headphones', 'description' => 'Wired and wireless headphones'],
                ],
            ],
            [
                'name'        => 'Clothing',
                'description' => 'Fashion and apparel',
                'is_active'   => true,
                'children'    => [
                    ['name' => 'Men',   'description' => 'Men clothing and accessories'],
                    ['name' => 'Women', 'description' => 'Women clothing and accessories'],
                    ['name' => 'Kids',  'description' => 'Kids clothing and accessories'],
                ],
            ],
        ];

        foreach ($categories as $data) {
            $parent = Category::create([
                'name'        => $data['name'],
                'slug'        => Str::slug($data['name']),
                'description' => $data['description'],
                'is_active'   => $data['is_active'],
                'parent_id'   => null,
            ]);

            foreach ($data['children'] as $child) {
                Category::create([
                    'name'        => $child['name'],
                    'slug'        => Str::slug($child['name']),
                    'description' => $child['description'],
                    'is_active'   => true,
                    'parent_id'   => $parent->id,
                ]);
            }
        }
    }
}

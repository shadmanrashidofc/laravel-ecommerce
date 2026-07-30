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
            ['name' => 'Electronics', 'status' => 'active'],
            ['name' => 'Clothing', 'status' => 'active'],
            ['name' => 'Home & Garden', 'status' => 'active'],
            ['name' => 'Sports & Outdoors', 'status' => 'active'],
            ['name' => 'Books', 'status' => 'active'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'status' => $category['status'],
            ]);
        }
    }
}

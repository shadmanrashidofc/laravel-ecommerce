<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $colors = ['Black', 'White', 'Red', 'Blue', 'Green', 'Gray', 'Navy', 'Pink'];
        $sizes = ['S', 'M', 'L', 'XL', 'XXL', 'One Size'];

        $productsData = [
            ['category' => 'Electronics', 'items' => [
                ['name' => 'Wireless Bluetooth Headphones', 'price' => 79.99, 'discount' => 59.99],
                ['name' => 'Smart Watch Pro', 'price' => 299.99, 'discount' => 249.99],
                ['name' => 'Portable Bluetooth Speaker', 'price' => 49.99, 'discount' => null],
                ['name' => 'Wireless Gaming Mouse', 'price' => 89.99, 'discount' => 69.99],
            ]],
            ['category' => 'Clothing', 'items' => [
                ['name' => 'Cotton T-Shirt', 'price' => 29.99, 'discount' => 19.99],
                ['name' => 'Denim Jeans', 'price' => 59.99, 'discount' => null],
                ['name' => 'Winter Jacket', 'price' => 149.99, 'discount' => 119.99],
                ['name' => 'Sports Sneakers', 'price' => 89.99, 'discount' => 79.99],
            ]],
            ['category' => 'Home & Garden', 'items' => [
                ['name' => 'LED Desk Lamp', 'price' => 39.99, 'discount' => 29.99],
                ['name' => 'Comfort Pillow Set', 'price' => 49.99, 'discount' => null],
                ['name' => 'Garden Tool Kit', 'price' => 69.99, 'discount' => 54.99],
                ['name' => 'Indoor Plant Pot', 'price' => 24.99, 'discount' => 19.99],
            ]],
            ['category' => 'Sports & Outdoors', 'items' => [
                ['name' => 'Yoga Mat Premium', 'price' => 34.99, 'discount' => null],
                ['name' => 'Fitness Tracker Band', 'price' => 59.99, 'discount' => 44.99],
                ['name' => 'Camping Tent 4-Person', 'price' => 199.99, 'discount' => 159.99],
                ['name' => 'Stainless Water Bottle', 'price' => 24.99, 'discount' => 19.99],
            ]],
            ['category' => 'Books', 'items' => [
                ['name' => 'JavaScript: The Complete Guide', 'price' => 44.99, 'discount' => 34.99],
                ['name' => 'Laravel Mastery', 'price' => 39.99, 'discount' => null],
                ['name' => 'Clean Code Principles', 'price' => 49.99, 'discount' => 39.99],
                ['name' => 'Web Development Cookbook', 'price' => 35.99, 'discount' => 27.99],
            ]],
        ];

        foreach ($productsData as $categoryData) {
            $category = $categories->where('name', $categoryData['category'])->first();

            foreach ($categoryData['items'] as $item) {
                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'slug' => Str::slug($item['name']),
                    'description' => fake()->paragraphs(2, true),
                    'thumbnail' => 'products/placeholder.jpg',
                    'price' => $item['price'],
                    'discount_price' => $item['discount'],
                    'status' => 'active',
                ]);

                $variantCount = rand(2, 5);
                $usedCombinations = [];

                for ($i = 0; $i < $variantCount; $i++) {
                    $color = fake()->randomElement($colors);
                    $size = fake()->randomElement($sizes);
                    $combination = $color.'-'.$size;

                    if (in_array($combination, $usedCombinations)) {
                        $i--;

                        continue;
                    }
                    $usedCombinations[] = $combination;

                    $variantPrice = $product->discount_price ?? $product->price;
                    if (fake()->boolean(30)) {
                        $variantPrice += fake()->randomFloat(2, 10, 30);
                    }

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => 'SKU-'.strtoupper(Str::random(8)),
                        'size' => $size,
                        'color' => $color,
                        'price' => $variantPrice,
                        'stock' => rand(5, 100),
                    ]);
                }

                $imageCount = rand(2, 4);
                for ($i = 0; $i < $imageCount; $i++) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'products/product-'.($i + 1).'.jpg',
                    ]);
                }
            }
        }
    }
}

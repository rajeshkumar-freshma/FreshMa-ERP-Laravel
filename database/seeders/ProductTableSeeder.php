<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->delete();
        
        $products = [
            [
                'name' => 'Octobus',
                'slug' => 'product-1',
                'sku_code' => 'SKU123456',
                'hsn_code' => 'HSN1234',
                'product_description' => 'Description for Octobus',
                'status' => 1,
                'item_type_id' => 1,
                'unit_id' => 1,
                'tax_type' => 1,
                'tax_id' => 1,
                'meta_title' => 'Octobus Title',
                'meta_description' => 'Octobus Description',
                'meta_keywords' => 'Product, 1',
                'image' => 'https://example.com/image1.jpg', // Example image URL
                'image_path' => 'path/to/image1.jpg', // Example image path
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sea Lion',
                'slug' => 'product-2',
                'sku_code' => 'SKU654321',
                'hsn_code' => 'HSN5678',
                'product_description' => 'Description for Sea Lion',
                'status' => 1,
                'item_type_id' => 2,
                'unit_id' => 1,
                'tax_type' => 1,
                'tax_id' => 1,
                'meta_title' => 'Sea Lion Title',
                'meta_description' => 'Sea Lion Description',
                'meta_keywords' => 'Product, 2',
                'image' => 'https://example.com/image2.jpg', // Example image URL
                'image_path' => 'path/to/image2.jpg', // Example image path
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sea Horse',
                'slug' => 'product-3',
                'sku_code' => 'SKU654321',
                'hsn_code' => 'HSN5678',
                'product_description' => 'Description for Sea Horse',
                'status' => 1,
                'item_type_id' => 3,
                'unit_id' => 1,
                'tax_type' => 1,
                'tax_id' => 1,
                'meta_title' => 'Sea Horse Title',
                'meta_description' => 'Sea Horse Description',
                'meta_keywords' => 'Product, 2',
                'image' => 'https://example.com/image2.jpg', // Example image URL
                'image_path' => 'path/to/image2.jpg', // Example image path
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more products as needed
        ];

        // Insert the dummy products into the database
        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

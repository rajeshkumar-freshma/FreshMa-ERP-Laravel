<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ItemType;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    public function run(): void
    {
       $itemType = \App\Models\ItemType::first();
       if (!$itemType) {
            $itemType = \App\Models\ItemType::create([
                'name' => 'Default Item Type',
                'status' => 1,
            ]);
       }

       $unitId = \DB::table('units')->value('id');
       if (!$unitId) {
            $this->call(UnitsTableSeeder::class);
            $unitId = \DB::table('units')->value('id');
       }

       $taxId = \DB::table('tax_rates')->value('id');
       if (!$taxId) {
            $this->call(TaxRatesTableSeeder::class);
            $taxId = \DB::table('tax_rates')->value('id');
       }

        $products = [
            [
                'name' => 'Octobus',
                'slug' => 'product-1',
                'sku_code' => 'SKU123456',
                'hsn_code' => 'HSN1234',
                'product_description' => 'Description for Octobus',
                'status' => 1,
                'item_type_id' => $itemType->id,
                'unit_id' => $unitId,
                'tax_type' => 1,
                'tax_id' => $taxId,
                'meta_title' => 'Octobus Title',
                'meta_description' => 'Octobus Description',
                'meta_keywords' => 'Product, 1',
                'image' => 'https://example.com/image1.jpg',
                'image_path' => 'path/to/image1.jpg',
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
                'item_type_id' => $itemType->id,
                'unit_id' => $unitId,
                'tax_type' => 1,
                'tax_id' => $taxId,
                'meta_title' => 'Sea Lion Title',
                'meta_description' => 'Sea Lion Description',
                'meta_keywords' => 'Product, 2',
                'image' => 'https://example.com/image2.jpg',
                'image_path' => 'path/to/image2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sea Horse',
                'slug' => 'product-3',
                'sku_code' => 'SKU999999',
                'hsn_code' => 'HSN9999',
                'product_description' => 'Description for Sea Horse',
                'status' => 1,
                'item_type_id' => $itemType->id,
                'unit_id' => $unitId,
                'tax_type' => 1,
                'tax_id' => $taxId,
                'meta_title' => 'Sea Horse Title',
                'meta_description' => 'Sea Horse Description',
                'meta_keywords' => 'Product, 3',
                'image' => 'https://example.com/image3.jpg',
                'image_path' => 'path/to/image3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

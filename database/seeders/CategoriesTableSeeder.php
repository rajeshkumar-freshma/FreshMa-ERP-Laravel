<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('categories')->delete();

        DB::unprepared('SET IDENTITY_INSERT categories ON');

        DB::table('categories')->insert(array (
            0 =>
            array (
                'id' => '1',
                'name' => 'See Fish',
                'parent_id' => NULL,
                'slug' => 'sea-fish',
                'meta_title' => 'SeaFish',
                'meta_description' => 'seafish',
                'meta_keywords' => 'seafish',
                'description' => 'Sea fish- Can Access all kinds of sea fish over here',
                'image' => '20230407070919_sea_fish4.jpeg',
                'image_path' => 'media/category/2023/04',
                'position' => NULL,
                'is_featured' => '1',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:09:19.160',
                'updated_at' => '2023-04-07 07:09:19.160',
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => '2',
                'name' => 'Lobsters',
                'parent_id' => NULL,
                'slug' => 'lob',
                'meta_title' => 'lobsters',
                'meta_description' => 'lobsters',
                'meta_keywords' => 'lobsters',
                'description' => 'lobsters',
            'image' => '20230407071003_download_(6).jpeg',
                'image_path' => 'media/category/2023/04',
                'position' => NULL,
                'is_featured' => '0',
                'status' => '0',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:10:03.733',
                'updated_at' => '2023-04-07 07:10:03.733',
                'deleted_at' => NULL,
            ),
            2 =>
            array (
                'id' => '3',
                'name' => 'Dry Fish',
                'parent_id' => NULL,
                'slug' => 'dry-fish',
                'meta_title' => 'Dry Fish',
                'meta_description' => 'Dry Fish',
                'meta_keywords' => 'Dry Fish',
                'description' => 'Dry Fish',
            'image' => '20230407071040_download_(9).jpeg',
                'image_path' => 'media/category/2023/04',
                'position' => NULL,
                'is_featured' => '0',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:10:40.993',
                'updated_at' => '2023-04-07 07:10:40.993',
                'deleted_at' => NULL,
            ),
            3 =>
            array (
                'id' => '5',
                'name' => 'Fresh Water',
                'parent_id' => NULL,
                'slug' => 'fresh-water',
                'meta_title' => 'fresh-water',
                'meta_description' => 'fresh-water',
                'meta_keywords' => 'fresh-water',
                'description' => 'fresh-water',
            'image' => '20230407071131_download_(3).jpeg',
                'image_path' => 'media/category/2023/04',
                'position' => NULL,
                'is_featured' => '0',
                'status' => '0',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:11:31.843',
                'updated_at' => '2023-04-07 07:11:31.843',
                'deleted_at' => NULL,
            ),
            4 =>
            array (
                'id' => '7',
                'name' => 'Ready to COOK',
                'parent_id' => NULL,
                'slug' => 'ready-to-cook',
                'meta_title' => 'Ready to COOK',
                'meta_description' => 'Ready to COOK',
                'meta_keywords' => 'Ready to COOK',
                'description' => 'Ready to COOK',
                'image' => NULL,
                'image_path' => NULL,
                'position' => NULL,
                'is_featured' => '1',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:12:12.977',
                'updated_at' => '2023-04-07 07:12:12.977',
                'deleted_at' => NULL,
            ),
            5 =>
            array (
                'id' => '9',
                'name' => 'Small Fish',
                'parent_id' => NULL,
                'slug' => 'small-fish',
                'meta_title' => 'Small Fish',
                'meta_description' => 'Small Fish',
                'meta_keywords' => 'Small Fish',
                'description' => 'Small Fish',
                'image' => NULL,
                'image_path' => NULL,
                'position' => NULL,
                'is_featured' => '0',
                'status' => '0',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2023-04-07 07:15:21.537',
                'updated_at' => '2023-04-07 07:15:21.537',
                'deleted_at' => NULL,
            ),
        ));

        \DB::unprepared('SET IDENTITY_INSERT categories OFF');


    }
}

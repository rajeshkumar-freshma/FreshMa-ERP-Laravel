<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultImageSeeder extends Seeder
{
    public function run()
    {
        // // Read the image file from the folder
        // $imagePath = public_path('media/default_image/fav.png');
        // $imageContent = file_get_contents($imagePath);

        // // Insert the image content into the database
        // DB::table('system_site_settings')->insert([
        //     [
        //         'image' => $imageContent,
        //         'image_path' => 'Description',
        //     ],
        // ]);
    }
}

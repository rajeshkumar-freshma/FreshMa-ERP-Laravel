<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserInfosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('user_infos')->delete();
        
        \DB::table('user_infos')->insert(array (
            0 => 
            array (
                'id' => '1',
                'admin_type' => '2',
                'user_id' => '1',
                'admin_id' => NULL,
                'company' => 'FreshMa',
                'website' => 'https://www.freshma.in/',
                'address' => NULL,
                'country_id' => '1',
                'state_id' => NULL,
                'city_id' => NULL,
                'currency_id' => NULL,
                'gst_number' => NULL,
                'joined_at' => NULL,
                'image' => NULL,
                'image_path' => NULL,
                'created_at' => '2023-04-01 08:27:16.187',
                'updated_at' => '2023-04-01 08:27:16.187',
            ),
            1 => 
            array (
                'id' => '2',
                'admin_type' => '1',
                'user_id' => NULL,
                'admin_id' => '1',
                'company' => 'FreshMa',
                'website' => 'https://www.freshma.in/',
                'address' => NULL,
                'country_id' => '1',
                'state_id' => NULL,
                'city_id' => NULL,
                'currency_id' => NULL,
                'gst_number' => NULL,
                'joined_at' => NULL,
                'image' => NULL,
                'image_path' => NULL,
                'created_at' => '2023-04-01 08:27:16.363',
                'updated_at' => '2023-04-01 08:27:16.363',
            ),
            2 => 
            array (
                'id' => '6',
                'admin_type' => '2',
                'user_id' => '5',
                'admin_id' => NULL,
                'company' => 'FishKaro1',
                'website' => 'www.fishkaro.com',
                'address' => 'chennai',
                'country_id' => '101',
                'state_id' => '35',
                'city_id' => '3659',
                'currency_id' => '101',
                'gst_number' => '33AAKCC1217A1ZG',
                'joined_at' => '2023-04-07 00:00:00.000',
                'image' => '20230407075158_male.jpg',
                'image_path' => 'media/vendor/2023/04',
                'created_at' => '2023-04-07 07:51:58.147',
                'updated_at' => '2023-04-07 07:52:38.300',
            ),
            3 => 
            array (
                'id' => '8',
                'admin_type' => '1',
                'user_id' => NULL,
                'admin_id' => '3',
                'company' => 'CodeTez Technologies',
                'website' => 'www.fishkaro.com',
                'address' => 'No 7C Kurumberi Kollakottai, Kurumberi Village and Post',
                'country_id' => '101',
                'state_id' => '35',
                'city_id' => '3659',
                'currency_id' => '101',
                'gst_number' => '33AAKCC1217A1IG',
                'joined_at' => '2023-04-27 00:00:00.000',
                'image' => NULL,
                'image_path' => NULL,
                'created_at' => '2023-04-12 12:36:39.227',
                'updated_at' => '2023-04-12 12:57:39.017',
            ),
            4 => 
            array (
                'id' => '10',
                'admin_type' => '2',
                'user_id' => '7',
                'admin_id' => NULL,
                'company' => 'CodeTez Technologies',
                'website' => 'www.fishkaro.com',
                'address' => 'Saidapet',
                'country_id' => '101',
                'state_id' => '35',
                'city_id' => '3659',
                'currency_id' => '101',
                'gst_number' => '33AAKCC12117A1ZG',
                'joined_at' => '2023-04-12 00:00:00.000',
                'image' => NULL,
                'image_path' => NULL,
                'created_at' => '2023-04-12 13:03:19.613',
                'updated_at' => '2023-04-12 13:03:19.613',
            ),
        ));
        
        
    }
}
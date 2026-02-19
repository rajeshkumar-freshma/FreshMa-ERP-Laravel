<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Core\CommonComponent;


class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $data = $this->data();

        // foreach ($data as $value) {
        //     $slug = CommonComponent::slugCreate($value['name'], null);
        //     // dd($slug);
        //     PaymentType::create([
        //         'payment_type' => $value['name'],
        //         'slug' => $slug,
        //     ]);
        // }

        $payment_types = array(
            array(

                "payment_type" => "Cash On Hand",
                "slug" => "cash-on-hand",
                "payment_category" => 1,
                "machine_number" => null,
                "store_id" => 2,
                "file" => null,
                "file_path" => null,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "created_at" => "2024-04-25T09:58:01.257Z",
                "updated_at" => "2024-06-13T05:21:03.580Z",
                "deleted_at" => null
            ),
            array(

                "payment_type" => "GPay",
                "slug" => "gpay",
                "payment_category" => null,
                "machine_number" => null,
                "store_id" => null,
                "file" => null,
                "file_path" => null,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "created_at" => "2024-04-25T09:58:01.997Z",
                "updated_at" => "2024-04-25T09:58:01.997Z",
                "deleted_at" => null
            ),
            array(

                "payment_type" => "PhonePe",
                "slug" => "phonepe",
                "payment_category" => null,
                "machine_number" => null,
                "store_id" => null,
                "file" => null,
                "file_path" => null,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "created_at" => "2024-04-25T09:58:02.330Z",
                "updated_at" => "2024-04-25T09:58:02.330Z",
                "deleted_at" => null
            ),
            array(

                "payment_type" => "Card",
                "slug" => "card",
                "payment_category" => null,
                "machine_number" => null,
                "store_id" => null,
                "file" => null,
                "file_path" => null,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "created_at" => "2024-04-25T09:58:02.647Z",
                "updated_at" => "2024-04-25T09:58:02.647Z",
                "deleted_at" => null
            ),
            array(

                "payment_type" => "Cheque",
                "slug" => "cheque",
                "payment_category" => 3,
                "machine_number" => null,
                "store_id" => 2,
                "file" => null,
                "file_path" => null,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "created_at" => "2024-04-25T09:58:02.957Z",
                "updated_at" => "2024-06-13T05:20:47.013Z",
                "deleted_at" => null
            )
        );
        foreach ($payment_types as $payment_type) {
            PaymentType::create($payment_type);
        }
    }

    public function data()
    {
        return [
            ['name' => 'Cash On Hand'],
            ['name' => 'GPay'],
            ['name' => 'PhonePe'],
            ['name' => 'Card'],
            ['name' => 'Cheque'],
        ];
    }
}

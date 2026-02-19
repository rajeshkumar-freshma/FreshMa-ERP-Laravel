<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        DB::table('designations')->delete();

        DB::beginTransaction();

        DB::unprepared('SET IDENTITY_INSERT currencies ON');

        $data = $this->data();

        foreach ($data as $value) {
            Designation::create([
                'name' => $value['name'],
            ]);
        }

        DB::unprepared('SET IDENTITY_INSERT designations OFF');

        DB::commit();
    }

    public function data()
    {
        return [
            ['name' => 'Senior HR'],
            ['name' => 'Senior Manager'],
            ['name' => 'Cutter'],
        ];
    }
}

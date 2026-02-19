<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        DB::table('departments')->delete();

        DB::beginTransaction();

        DB::unprepared('SET IDENTITY_INSERT currencies ON');
        
        $data = $this->data();

        foreach ($data as $value) {
            Department::create([
                'name' => $value['name'],
            ]);
        }

        DB::unprepared('SET IDENTITY_INSERT departments OFF');

        DB::commit();
    }

    public function data()
    {
        return [
            ['name' => 'HR'],
            ['name' => 'Manager'],
        ];
    }
}

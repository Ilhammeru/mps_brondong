<?php

namespace Database\Seeders;

use App\Models\EmployeeStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmployeeStatus::truncate();

        $data = [
            ['name' => 'staff'],
            ['name' => 'mandor'],
            ['name' => 'borongan']
        ];

        EmployeeStatus::insert($data);
    }
}

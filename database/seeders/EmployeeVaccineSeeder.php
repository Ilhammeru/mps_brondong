<?php

namespace Database\Seeders;

use App\Models\EmployeeVaccine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeVaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmployeeVaccine::truncate();
        $data = [
            [
                'user_id' => 1,
                'vaccine_id' => 1,
                'vaccine_grade' => 1,
                'vaccine_date' => date('Y-m-d', strtotime('2021-08-08')),
            ],
            [
                'user_id' => 1,
                'vaccine_id' => 1,
                'vaccine_grade' => 2,
                'vaccine_date' => date('Y-m-d', strtotime('2021-09-08')),
            ],
            [
                'user_id' => 1,
                'vaccine_id' => 1,
                'vaccine_grade' => 3,
                'vaccine_date' => date('Y-m-d', strtotime('2021-10-09')),
            ],
            [
                'user_id' => 2,
                'vaccine_id' => 2,
                'vaccine_grade' => 1,
                'vaccine_date' => date('Y-m-d', strtotime('2021-12-01')),
            ],
            [
                'user_id' => 2,
                'vaccine_id' => 2,
                'vaccine_grade' => 2,
                'vaccine_date' => date('Y-m-d', strtotime('2022-04-01')),
            ],
        ];

        EmployeeVaccine::insert($data);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Vaccine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vaccine::truncate();
        $vaccine = [
            [
                'name' => 'sinovac',
                'next_period_1' => 30,
                'next_period_1' => 30,
                'next_period_1' => 30,
                'next_period_2' => 30,
                'next_period_2' => 30,
                'next_period_2' => 30,
                'next_period_3' => 30,
                'next_period_3' => 30,
                'next_period_3' => 30,
            ],
            [
                'name' => 'astra',
                'next_period_1' => 30,
                'next_period_1' => 30,
                'next_period_1' => 30,
                'next_period_2' => 30,
                'next_period_2' => 30,
                'next_period_2' => 30,
                'next_period_3' => 30,
                'next_period_3' => 30,
                'next_period_3' => 30,
            ],
            [
                'name' => 'pfizer',
                'next_period_1' => 30,
                'next_period_1' => 30,
                'next_period_1' => 30,
                'next_period_2' => 30,
                'next_period_2' => 30,
                'next_period_2' => 30,
                'next_period_3' => 30,
                'next_period_3' => 30,
                'next_period_3' => 30,
            ],
        ];

        Vaccine::insert($vaccine);
    }
}

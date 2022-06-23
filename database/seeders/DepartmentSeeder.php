<?php

namespace Database\Seeders;

use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::truncate();

        $data = [
            [
                'name' => 'HRD',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Keuangan & Umum',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Produksi',
                'created_at' => Carbon::now()
            ]
        ];

        Department::insert($data);
    }
}

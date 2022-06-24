<?php

namespace Database\Seeders;

use App\Models\Division;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Division::truncate();

        $division = [
            ['name' => 'keuangan', 'department_id' => 2, 'created_at' => Carbon::now()],
            ['name' => 'HRD', 'department_id' => 1, 'created_at' => Carbon::now()],
            ['name' => 'Admin', 'department_id' => 1, 'created_at' => Carbon::now()],
            ['name' => 'produksi', 'department_id' => 3, 'created_at' => Carbon::now()],
        ];

        Division::insert($division);
    }
}

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
            ['name' => 'keuangan', 'created_at' => Carbon::now()],
            ['name' => 'HRD', 'created_at' => Carbon::now()],
            ['name' => 'produksi', 'created_at' => Carbon::now()],
        ];

        Division::insert($division);
    }
}

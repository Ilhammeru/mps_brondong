<?php

namespace Database\Seeders;

use App\Models\Division;
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
            ['name' => 'keuangan'],
            ['name' => 'HRD'],
            ['name' => 'produksi'],
        ];

        Division::insert($division);
    }
}

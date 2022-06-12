<?php

namespace Database\Seeders;

use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::truncate();

        $position = [
            ['name' => 'SPV', 'division_id' => 2, 'created_at' => Carbon::now()],
            ['name' => 'Leader', 'division_id' => 2, 'created_at' => Carbon::now()]
        ];

        Position::insert($position);
    }
}

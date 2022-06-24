<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();]

        $this->call([
            DivisionSeeder::class,
            PositionSeeder::class,
            EmployeeSeeder::class,
            UsersSeeder::class,
            VaccineSeeder::class,
            IndoRegionProvinceSeeder::class,
            IndoRegionRegencySeeder::class,
            IndoRegionDistrictSeeder::class,
            IndoRegionVillageSeeder::class,
            EmployeeVaccineSeeder::class,
            DepartmentSeeder::class,
            EmployeeStatusSeeder::class
        ]);
    }
}

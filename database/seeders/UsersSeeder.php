<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $user = [
            [
                'name' => 'Rany Desy Kurniasari',
                'email' => 'ranydesykurniasari@gmail.com',
                'username' => 'randeka',
                'password' => Hash::make('randeka'),
                'division_id' => 2,
                'position_id' => 1,
                'start_working_date' => date('Y-m-d', strtotime('2021-10-01')),
                'start_working_month' => date('m', strtotime('2021-10-01')),
                'role' => 'admin'
            ],
            [
                'name' => 'Pak Yit',
                'email' => 'pakyit@gmail.com',
                'username' => 'pakyit',
                'password' => Hash::make('pakyit'),
                'division_id' => 2,
                'position_id' => 1,
                'start_working_date' => date('Y-m-d', strtotime('2021-10-01')),
                'start_working_month' => date('m', strtotime('2021-10-01')),
                'role' => 'satpam'
            ]
        ];

        User::insert($user);
    }
}

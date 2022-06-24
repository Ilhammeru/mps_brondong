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
                'role' => 'admin',
                'employee_id' => 1
            ],
            [
                'name' => 'Ilham Meru Gumilang',
                'email' => 'gumilang.dev@gmail.com',
                'username' => 'ilham',
                'password' => Hash::make('ilham'),
                'role' => 'admin',
                'employee_id' => 2
            ],
            [
                'name' => 'Pak Yit',
                'email' => 'pakyit@gmail.com',
                'username' => 'pakyit',
                'password' => Hash::make('pakyit'),
                'role' => 'satpam',
                'employee_id' => 3
            ]
        ];

        User::insert($user);
    }
}

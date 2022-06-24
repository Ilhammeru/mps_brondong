<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Employee::truncate();
        
        $employee = [
            [
                'employee_id' => '12487837343',
                'name' => 'Rany Desy Kurniasari',
                'aliases' => 'Ceceran',
                'email' => 'ranydesykurniasari@gmail.com',
                'phone' => '085795327357',
                'nik' => '3573042405960006',
                'gender' => 'P',
                'birth_date' => date('Y-m-d', strtotime('1995-12-01')),
                'is_whatsapp' => true,
                'address' => 'Jl. Bandulan VI no 788',
                'province_id' => 35,
                'regency_id' => '3573',
                'district_id' => '3573020',
                'village_id' => '3573020008',
                'primary_school' => 'SD Naik Turun',
                'primary_school_graduate' => '2008',
                'junior_high_school' => 'SMP 1 Paciran',
                'junior_high_school_graduate' => '2011',
                'junior_high_school_gpa' => 39.80,
                'high_school' => 'SMA 2 Bojonegoro',
                'high_school_graduate' => '2013',
                'high_school_gpa' => 40.00,
                'university' => 'Universitas Brawijaya',
                'university_graduate' => '2021',
                'university_gpa' => 4.0,
                'wali_name' => 'Wali Rany',
                'wali_phone' => '08827727222',
                'wali_address' => 'Jl. werkudoro',
                'current_vaccine_level' => 3,
                'employee_status' => 1,
                'date_in_contract' => date('Y-m-d', strtotime('2021-05-01')),
                'date_in_permanent' => date('Y-m-d', strtotime('2021-10-01')),
                'department_id' => 1,
                'division_id' => 2,
                'position_id' => 3,
                'employee_status_id' => 1,
                'bank_account_name' => 'Rany Desy Kurniasari',
                'bank_account_number' => '034839834',
                'bank_name' => 'BNI'
            ],
            [
                'employee_id' => '12487837343',
                'name' => 'Ilham Meru Gumilang',
                'aliases' => 'ilham',
                'email' => 'gumilang.dev@gmail.com',
                'phone' => '085795327357',
                'nik' => '3573042405960004',
                'gender' => 'L',
                'birth_date' => date('Y-m-d', strtotime('1996-05-24')),
                'is_whatsapp' => true,
                'address' => 'Jl. Bandulan VI no 788',
                'province_id' => 35,
                'regency_id' => '3573',
                'district_id' => '3573020',
                'village_id' => '3573020008',
                'primary_school' => 'SD Naik Turun',
                'primary_school_graduate' => '2008',
                'junior_high_school' => 'SMP 1 Paciran',
                'junior_high_school_graduate' => '2011',
                'junior_high_school_gpa' => 39.80,
                'high_school' => 'SMA 2 Bojonegoro',
                'high_school_graduate' => '2013',
                'high_school_gpa' => 40.00,
                'university' => NULL,
                'university_graduate' => NULL,
                'university_gpa' => NULL,
                'wali_name' => 'Wali Rany',
                'wali_phone' => '08827727222',
                'wali_address' => 'Jl. werkudoro',
                'current_vaccine_level' => 2,
                'employee_status' => 0,
                'date_in_contract' => NULL,
                'date_in_permanent' => date('Y-m-d', strtotime('2021-10-01')),
                'department_id' => 1,
                'division_id' => 2,
                'position_id' => 3,
                'employee_status_id' => 1,
                'bank_account_name' => 'Ilham Meru Gumilang',
                'bank_account_number' => '034839834',
                'bank_name' => 'BNI'
            ],
            [
                'employee_id' => '12487837343',
                'name' => 'Pak Yit',
                'aliases' => 'pak yit',
                'email' => 'pakyit@gmail.com',
                'phone' => '085795327357',
                'nik' => '3573042405960004',
                'gender' => 'L',
                'birth_date' => date('Y-m-d', strtotime('1996-05-24')),
                'is_whatsapp' => true,
                'address' => 'Jl. Bandulan VI no 788',
                'province_id' => 35,
                'regency_id' => '3573',
                'district_id' => '3573020',
                'village_id' => '3573020008',
                'primary_school' => 'SD Naik Turun',
                'primary_school_graduate' => '2008',
                'junior_high_school' => 'SMP 1 Paciran',
                'junior_high_school_graduate' => '2011',
                'junior_high_school_gpa' => 39.80,
                'high_school' => 'SMA 2 Bojonegoro',
                'high_school_graduate' => '2013',
                'high_school_gpa' => 40.00,
                'university' => NULL,
                'university_graduate' => NULL,
                'university_gpa' => NULL,
                'wali_name' => 'Wali Pak Yit',
                'wali_phone' => '08827727222',
                'wali_address' => 'Jl. werkudoro',
                'current_vaccine_level' => 2,
                'employee_status' => 0,
                'date_in_contract' => NULL,
                'date_in_permanent' => date('Y-m-d', strtotime('2021-10-01')),
                'department_id' => 1,
                'division_id' => 2,
                'position_id' => 3,
                'employee_status_id' => 1,
                'bank_account_name' => 'Pak Yit',
                'bank_account_number' => '034839834',
                'bank_name' => 'BNI'
            ],
        ];

        Employee::insert($employee);
    }
}

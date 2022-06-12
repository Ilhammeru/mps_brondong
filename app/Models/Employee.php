<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $fillable = [
        'employee_id',
        'name',
        'aliases',
        'email',
        'email_verified_at',
        'phone',
        'is_whatsapp',
        'gender',
        'birth_date',
        'address',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'nik',
        'father_name',
        'mother_name',
        'father_job',
        'mother_job',
        'father_address',
        'mother_address',
        'emergency_contact_name_1',
        'emergency_contact_number_1',
        'emergency_contact_siblings_1',
        'emergency_contact_name_2',
        'emergency_contact_number_2',
        'emergency_contact_siblings_2',
        'primary_school',
        'primary_school_graduate',
        'primary_school_gpa',
        'junior_high_school',
        'junior_high_school_graduate',
        'junior_high_school_gpa',
        'high_school',
        'high_school_graduate',
        'high_school_gpa',
        'university',
        'university_graduate',
        'university_gpa',
        'work_experience_name_1',
        'work_experience_position_1',
        'work_experience_in_1',
        'work_experience_out_1',
        'work_experience_name_2',
        'work_experience_position_2',
        'work_experience_in_2',
        'work_experience_out_2',
        'work_experience_name_3',
        'work_experience_position_3',
        'work_experience_in_3',
        'work_experience_out_3',
        'current_vaccine_level',
        'employee_status',
        'date_in_contract',
        'date_in_permanent',
        'contract_doc',
        'permanent_doc',
        'division_id',
        'position_id',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'bpjs_ketenagakerjaan_number',
        'bpjs_kesehatan_number'
    ];

    public function userVaccine(): HasOne
    {
        return $this->hasOne(EmployeeVaccine::class, 'user_id', 'id');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    /**
     * Employee belongs to province.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'village_id', 'id');
    }
}

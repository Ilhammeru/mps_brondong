<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeNpwp extends Model
{
    use HasFactory;

    protected $table = 'employee_npwp';
    protected $fillable = [
        'user_id',
        'npwp',
        'npwp_status_id'
    ];
}

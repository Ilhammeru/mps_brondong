<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use function PHPSTORM_META\map;

class EmployeeVaccine extends Model
{
    use HasFactory;

    protected $table = 'employee_vaccine';
    protected $fillable = [
        'user_id',
        'vaccine_id',
        'vaccine_grade',
        'vaccine_date'
    ];

    public function vaccine():BelongsTo
    {
        return $this->belongsTo(Vaccine::class, 'vaccine_id', 'id');
    }
}

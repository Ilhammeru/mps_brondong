<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenstruationLeave extends Model
{
    use HasFactory;

    protected $table = 'menstruation_leave';

    protected $fillable = [
        'employee_id',
        'leave_date_time',
        'approved_by',
        'checked_by'
    ];

    /**
     * Define Belongs to Relationship to Employee
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee():BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    /**
     * Define Belongs to Relationship to Employee
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvedBy():BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by', 'id');
    }

    /**
     * Define Belongs to Relationship to Employee
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checkedBy():BelongsTo
    {
        return $this->belongsTo(Employee::class, 'checked_by', 'id');
    }
}

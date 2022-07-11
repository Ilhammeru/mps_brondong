<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermissionLeaveOffice extends Model
{
    use HasFactory;

    /**
     * Define table name
     * 
     * @return string
     */
    protected $table = "leave_office_permit";

    /**
     * Define fillable field in database
     * 
     * @return array
     */
    protected $fillable = [
        'ticket_code',
        'employee_id',
        'leave_date_time',
        'notes',
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

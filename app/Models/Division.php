<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Division extends Model
{
    use HasFactory;

    protected $table = 'divisions';
    protected $fillable = ['name'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'division_id', 'id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'division_id', 'id');
    }

    public function position(): HasOne
    {
        return $this->hasOne(Division::class, 'division_id', 'id');
    }

    public function department():BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'division_id', 'id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'division_id', 'id');
    }
}

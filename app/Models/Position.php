<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Position extends Model
{
    use HasFactory;

    protected $table = 'positions';
    protected $fillable = ['division_id', 'name'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'division_id', 'id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'division_id', 'id');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'position_id', 'id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(User::class, 'division_id', 'id');
    }
}

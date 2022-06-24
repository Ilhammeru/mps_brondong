<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Department extends Model
{
    use HasFactory;

    protected $table = 'department';

    protected $fillable = ['name'];

    public function divisions():HasMany
    {
        return $this->hasMany(Division::class, 'department_id', 'id');
    }
}

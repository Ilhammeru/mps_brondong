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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Training extends Model
{
    use HasFactory;

    protected $table = 'training';
    protected $fillable = [
        'name',
        'pic',
        'description',
        'venue',
        'participant',
        'training_date',
        'status',
        'is_questionnaire'
    ];

    public function tags(): HasMany
    {
        return $this->hasMany(TrainingTag::class, 'training_id', 'id');
    }

    public function materials():HasMany
    {
        return $this->hasMany(TrainingMaterial::class, 'training_id', 'id');
    }

    public function photos():HasMany
    {
        return $this->hasMany(TrainingPhoto::class, 'training_id', 'id');
    }

    public function name():Attribute
    {
        return Attribute::make(
            get: fn($value) => ucfirst($value)
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';
    protected $fillable = [
        'name',
        'slug'
    ];

    public function name():Attribute
    {
        return Attribute::make(
            get: fn($value) => ucfirst($value)
        );
    }
}

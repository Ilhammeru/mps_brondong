<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    use HasFactory;

    protected $table = 'vaccine';
    protected $fillable = [
        'name',
        'next_period_1',
        'next_period_2',
        'next_period_3',
    ];
}

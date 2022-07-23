<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingTag extends Model
{
    use HasFactory;

    protected $table = 'training_tag';
    protected $fillable = [
        'tag_id',
        'training_id'
    ];
}

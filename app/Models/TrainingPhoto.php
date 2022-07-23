<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingPhoto extends Model
{
    use HasFactory;

    protected $table = 'training_photo';
    protected $fillable = [
        'training_id',
        'photo'
    ];

    public function training():BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_id', 'id');
    }
}

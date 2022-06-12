<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NpwpStatus extends Model
{
    use HasFactory;

    protected $table = "npwp_status";
    protected $fillable = [
        'status_name',
        'status_ptkp'
    ];
}

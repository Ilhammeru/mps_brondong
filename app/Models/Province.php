<?php

/*
 * This file is part of the IndoRegion package.
 *
 * (c) Azis Hapidin <azishapidin.com | azishapidin@gmail.com>
 *
 */

namespace App\Models;

use AzisHapidin\IndoRegion\Traits\ProvinceTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Province Model.
 */
class Province extends Model
{
    use ProvinceTrait;
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'provinces';

    /**
     * Province has many regencies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function regencies()
    {
        return $this->hasMany(Regency::class);
    }

    /**
     * Province has one regencies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function regency():HasOne
    {
        return $this->hasOne(Regency::class, 'province_id', 'id');
    }
}

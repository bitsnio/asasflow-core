<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenants extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // @asasflow:generated-fillable:start
        'name',
        'slug',
        'email',
        'contact_number',
        'address',
        'location_coordinates',
        'valid_until',
        'is_active',
        // @asasflow:generated-fillable:end
    ];

    protected $casts = [
        // @asasflow:generated-casts:start
        'address' => 'array',
        'location_coordinates' => 'array',
        'valid_until' => 'date',
        'is_active' => 'boolean',
        // @asasflow:generated-casts:end
    ];
}
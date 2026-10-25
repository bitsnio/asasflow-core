<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantDomains extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // @asasflow:generated-fillable:start
        'domain',
        'is_primary',
        'is_verified',
        // @asasflow:generated-fillable:end
    ];

    protected $casts = [
        // @asasflow:generated-casts:start
        'is_primary' => 'boolean',
        'is_verified' => 'boolean',
        // @asasflow:generated-casts:end
    ];
}
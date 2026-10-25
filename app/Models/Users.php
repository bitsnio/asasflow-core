<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // @asasflow:generated-fillable:start
        'tenant_id',
        'site_id',
        'firstName',
        'lastName',
        'email',
        'role_id',
        // @asasflow:generated-fillable:end
    ];

    protected $casts = [
        // @asasflow:generated-casts:start

        // @asasflow:generated-casts:end
    ];
}
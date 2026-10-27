<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Bitsnio\AsasFlow\Features\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Models\Users;

class Sites extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // @asasflow:generated-fillable:start
        'tenant_id',
        'name',
        'code',
        'phone',
        'email',
        'address',
        'is_active',
        // @asasflow:generated-fillable:end
    ];

    protected $casts = [
        // @asasflow:generated-casts:start
        'is_active' => 'boolean',
        // @asasflow:generated-casts:end
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(Users::class, 'site_id');
    }
}

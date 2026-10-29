<?php

namespace Modules\Core\Models;

use Bitsnio\AsasFlow\Features\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class Users extends Authenticatable implements JWTSubject
{
    use SoftDeletes, Notifiable, HasRoles;

    protected string $guard_name = 'api';

    protected $fillable = [
        // @asasflow:generated-fillable:start
        'tenant_id',
        'site_id',
        'firstName',
        'lastName',
        'email',
        'password',
        'email_verified_at',
        'role_id',
        // @asasflow:generated-fillable:end
    ];

    protected $casts = [
        // @asasflow:generated-casts:start
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        // @asasflow:generated-casts:end
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Sites::class, 'site_id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

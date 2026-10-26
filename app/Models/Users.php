<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;



class Users extends authenticatable implements JWTSubject
{
    use SoftDeletes, Notifiable;

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

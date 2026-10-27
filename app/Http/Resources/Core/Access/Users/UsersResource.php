<?php

namespace Modules\Core\Http\Resources\Core\Access\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class UsersResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        // @asasflow:generated-fields:start
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password,
            'email_verified_at' => $this->email_verified_at,
            'tenant_id' => $this->tenant_id,
            'site_id' => $this->site_id,
            'role_id' => $this->role_id,
        ];
        // @asasflow:generated-fields:end
    }
}
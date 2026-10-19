<?php

namespace Modules\Core\Http\Resources\Core\Access\Roles;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class RolesResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        // @asasflow:generated-fields:start
        return [
            'name' => $this->name,
            'permissions' => $this->permissions,
        ];        // @asasflow:generated-fields:end
    }
}
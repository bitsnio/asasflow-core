<?php

namespace Modules\Core\Http\Resources\Core\Tenancy\Sites;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class SitesResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        // @asasflow:generated-fields:start
        return [
            'name' => $this->name,
            'code' => $this->code,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'is_active' => $this->is_active,
        ];
        // @asasflow:generated-fields:end
    }
}
<?php

namespace Modules\Core\Http\Resources\Core\Tenancy\Tenants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class TenantsResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        // @asasflow:generated-fields:start
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'address' => $this->address,
            'location_coordinates' => $this->location_coordinates,
            'valid_until' => $this->valid_until,
            'is_active' => $this->is_active,
        ];        // @asasflow:generated-fields:end
    }
}
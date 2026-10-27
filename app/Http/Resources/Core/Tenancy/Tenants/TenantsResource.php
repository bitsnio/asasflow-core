<?php

namespace Modules\Core\Http\Resources\Core\Tenancy\Tenants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class TenantsResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        // @asasflow:generated-fields:start
        return parent::toArray($request);
        // @asasflow:generated-fields:end
    }
}
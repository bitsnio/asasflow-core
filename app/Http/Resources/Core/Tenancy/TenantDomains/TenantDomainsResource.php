<?php

namespace Modules\Core\Http\Resources\Core\Tenancy\TenantDomains;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class TenantDomainsResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        // @asasflow:generated-fields:start
        return parent::toArray($request);
        // @asasflow:generated-fields:end
    }
}
<?php

namespace Modules\Core\Http\Resources\Core\Tenancy\Sites;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class SitesResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        // @asasflow:generated-fields:start
        return parent::toArray($request);
        // @asasflow:generated-fields:end
    }
}
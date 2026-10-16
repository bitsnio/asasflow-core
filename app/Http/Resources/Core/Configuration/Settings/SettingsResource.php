<?php

namespace Modules\Core\Http\Resources\Core\Configuration\Settings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class SettingsResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        // @asasflow:generated-fields:start
        return parent::toArray($request);
        // @asasflow:generated-fields:end
    }
}
<?php

namespace Modules\Core\Http\Controllers\Core\Configuration\Settings;

use Bitsnio\AsasFlow\Foundation\Http\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Core\Http\Requests\Core\Configuration\Settings\SettingsRequest;

class SettingsController extends Controller
{
    use JsonResponseTrait;

    public function index(): JsonResponse
    {
        return $this->notImplementedResponse(
            'Index not implemented'
        );
    }

    public function store(SettingsRequest $request): JsonResponse
    {
        return $this->notImplementedResponse(
            'Store not implemented'
        );
    }

    public function show($settings): JsonResponse
    {
        return $this->notImplementedResponse(
            'Show not implemented'
        );
    }

    public function update(
        SettingsRequest $request,
        $settings
    ): JsonResponse {
        return $this->notImplementedResponse(
            'Update not implemented'
        );
    }

    public function destroy($settings): JsonResponse
    {
        return $this->notImplementedResponse(
            'Destroy not implemented'
        );
    }
}

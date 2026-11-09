<?php

namespace Modules\Core\Http\Controllers\Core\Configuration\Settings;

use Bitsnio\AsasFlow\Features\Settings\Http\Controllers\ModuleSettingsController;
use Bitsnio\AsasFlow\Features\Settings\Http\Requests\UpdateModuleSettingsRequest;
use Bitsnio\AsasFlow\Foundation\Http\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    use JsonResponseTrait;

    public function __construct(
        protected ModuleSettingsController $moduleSettingsController,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->moduleSettingsController->index($request);
    }

    public function store(UpdateModuleSettingsRequest $request): JsonResponse
    {
        $module = (string) $request->input('module', 'core');

        return $this->moduleSettingsController->update($request, $module);
    }

    public function show(Request $request, $settings): JsonResponse
    {
        return $this->moduleSettingsController->show($request, (string) $settings);
    }

    public function update(
        UpdateModuleSettingsRequest $request,
        $settings
    ): JsonResponse {
        return $this->moduleSettingsController->update($request, (string) $settings);
    }

    public function destroy($settings): JsonResponse
    {
        return $this->notImplementedResponse(
            'Destroy not implemented'
        );
    }
}

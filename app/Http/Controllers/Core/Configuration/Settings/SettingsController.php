<?php

namespace Modules\Core\Http\Controllers\Core\Configuration\Settings;

use Illuminate\Routing\Controller;
use Modules\Core\Http\Requests\Core\Configuration\Settings\SettingsRequest;
use Modules\Core\Http\Resources\Core\Configuration\Settings\SettingsResource;


class SettingsController extends Controller
{

    public function index()
    {
        return response()->json([
            'message' => 'Index not implemented',
        ], 501);
    }

    public function store(
        SettingsRequest $request
    ) {
        return response()->json([
            'message' => 'Store not implemented',
        ], 501);
    }

    public function show(
        $settings
    ) {
        return response()->json([
            'message' => 'Show not implemented',
        ], 501);
    }

    public function update(
        SettingsRequest $request,
        $settings
    ) {
        return response()->json([
            'message' => 'Update not implemented',
        ], 501);
    }

    public function destroy(
        $settings
    ) {
        return response()->json([
            'message' => 'Destroy not implemented',
        ], 501);
    }
}
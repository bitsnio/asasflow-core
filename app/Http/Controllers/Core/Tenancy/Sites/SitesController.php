<?php

namespace Modules\Core\Http\Controllers\Core\Tenancy\Sites;

use Illuminate\Routing\Controller;
use Modules\Core\Http\Requests\Core\Tenancy\Sites\SitesRequest;
use Modules\Core\Http\Resources\Core\Tenancy\Sites\SitesResource;


class SitesController extends Controller
{

    public function index()
    {
        return response()->json([
            'message' => 'Index not implemented',
        ], 501);
    }

    public function store(
        SitesRequest $request
    ) {
        return response()->json([
            'message' => 'Store not implemented',
        ], 501);
    }

    public function show(
        $sites
    ) {
        return response()->json([
            'message' => 'Show not implemented',
        ], 501);
    }

    public function update(
        SitesRequest $request,
        $sites
    ) {
        return response()->json([
            'message' => 'Update not implemented',
        ], 501);
    }

    public function destroy(
        $sites
    ) {
        return response()->json([
            'message' => 'Destroy not implemented',
        ], 501);
    }
}
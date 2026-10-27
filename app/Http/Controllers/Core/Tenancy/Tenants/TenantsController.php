<?php

namespace Modules\Core\Http\Controllers\Core\Tenancy\Tenants;

use Illuminate\Routing\Controller;
use Modules\Core\Http\Requests\Core\Tenancy\Tenants\TenantsRequest;
use Modules\Core\Http\Resources\Core\Tenancy\Tenants\TenantsResource;
use Illuminate\Http\Request;


class TenantsController extends Controller
{

    public function index()
    {
        return response()->json([
            'message' => 'Index not implemented',
        ], 501);
    }

    public function store(
        TenantsRequest $request
    ) {
        return response()->json([
            'message' => 'Store not implemented',
        ], 501);
    }

    public function show(
        $tenants
    ) {
        return response()->json([
            'message' => 'Show not implemented',
        ], 501);
    }

    public function update(
        TenantsRequest $request,
        $tenants
    ) {
        return response()->json([
            'message' => 'Update not implemented',
        ], 501);
    }

    public function destroy(
        $tenants
    ) {
        return response()->json([
            'message' => 'Destroy not implemented',
        ], 501);
    }

    public function activate(Request $request)
    {
        return response()->json([
            'message' => 'activate not implemented',
        ], 501);
    }

    public function suspend(Request $request)
    {
        return response()->json([
            'message' => 'suspend not implemented',
        ], 501);
    }
}
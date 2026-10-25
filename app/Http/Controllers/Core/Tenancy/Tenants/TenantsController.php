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
        return TenantsResource::collection(
            Tenants::paginate(15)
        );
    }

    public function store(
        TenantsRequest $request
    ) {
        $item = Tenants::create(
            $request->validated()
        );

        return new TenantsResource($item);
    }

    public function show(
        Tenants $tenants
    ) {
        return new TenantsResource($tenants);
    }

    public function update(
        TenantsRequest $request,
        Tenants $tenants
    ) {
        $tenants->update(
            $request->validated()
        );

        return new TenantsResource($tenants);
    }

    public function destroy(
        Tenants $tenants
    ) {
        $tenants->delete();

        return response()->noContent();
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

public function provision(Request $request)
{
    return response()->json([
        'message' => 'provision not implemented',
    ], 501);
}
}
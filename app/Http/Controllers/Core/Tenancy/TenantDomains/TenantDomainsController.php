<?php

namespace Modules\Core\Http\Controllers\Core\Tenancy\TenantDomains;

use Illuminate\Routing\Controller;
use Modules\Core\Http\Requests\Core\Tenancy\TenantDomains\TenantDomainsRequest;
use Modules\Core\Models\TenantDomains;
use Modules\Core\Http\Resources\Core\Tenancy\TenantDomains\TenantDomainsResource;
use Illuminate\Http\Request;


class TenantDomainsController extends Controller
{

    public function index()
    {
        return TenantDomainsResource::collection(
            TenantDomains::paginate(15)
        );
    }

    public function store(
        TenantDomainsRequest $request
    ) {
        $item = TenantDomains::create(
            $request->validated()
        );

        return new TenantDomainsResource($item);
    }

    public function show(
        TenantDomains $tenantDomains
    ) {
        return new TenantDomainsResource($tenantDomains);
    }

    public function update(
        TenantDomainsRequest $request,
        TenantDomains $tenantDomains
    ) {
        $tenantDomains->update(
            $request->validated()
        );

        return new TenantDomainsResource($tenantDomains);
    }

    public function destroy(
        TenantDomains $tenantDomains
    ) {
        $tenantDomains->delete();

        return response()->noContent();
    }

    public function verify(Request $request)
    {
        return response()->json([
            'message' => 'verify not implemented',
        ], 501);
    }
}
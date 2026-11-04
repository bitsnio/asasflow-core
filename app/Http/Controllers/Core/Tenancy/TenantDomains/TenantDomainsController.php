<?php

namespace Modules\Core\Http\Controllers\Core\Tenancy\TenantDomains;

use Bitsnio\AsasFlow\Features\Tenancy\Models\TenantDomain;
use Bitsnio\AsasFlow\Foundation\Http\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Http\Requests\Core\Tenancy\TenantDomains\TenantDomainsRequest;
use Modules\Core\Http\Resources\Core\Tenancy\TenantDomains\TenantDomainsResource;

class TenantDomainsController extends Controller
{
    use JsonResponseTrait;

    public function index(): JsonResponse
    {
        $items = $this->paginateQuery(
            TenantDomain::query(),
            15
        );

        return $this->paginatedResponse(
            TenantDomainsResource::collection($items),
            'Tenant domains retrieved successfully.'
        );
    }

    public function store(TenantDomainsRequest $request): JsonResponse
    {
        $item = TenantDomain::create(
            $request->validated()
        );

        return $this->createdResponse(
            new TenantDomainsResource($item),
            'Tenant domain created successfully.'
        );
    }

    public function show($tenant_domain): JsonResponse
    {
        $item = TenantDomain::findOrFail($tenant_domain);

        return $this->successResponse(
            new TenantDomainsResource($item),
            'Tenant domain retrieved successfully.'
        );
    }

    public function update(
        TenantDomainsRequest $request,
        $tenant_domain
    ): JsonResponse {
        $item = TenantDomain::findOrFail($tenant_domain);

        $item->update(
            $request->validated()
        );

        return $this->successResponse(
            new TenantDomainsResource($item),
            'Tenant domain updated successfully.'
        );
    }

    public function destroy($tenant_domain): JsonResponse
    {
        $item = TenantDomain::findOrFail($tenant_domain);

        $item->delete();

        return $this->successResponse(
            null,
            'Tenant domain deleted successfully.'
        );
    }

    public function verify(Request $request): JsonResponse
    {
        $domain = TenantDomain::findOrFail($request->input('id'));
        $domain->update(['is_verified' => true]);

        return $this->successResponse(
            new TenantDomainsResource($domain),
            'Tenant domain verified successfully.'
        );
    }
}

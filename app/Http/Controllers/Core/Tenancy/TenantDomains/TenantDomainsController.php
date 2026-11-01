<?php

namespace Modules\Core\Http\Controllers\Core\Tenancy\TenantDomains;

use Bitsnio\AsasFlow\Foundation\Http\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Http\Requests\Core\Tenancy\TenantDomains\TenantDomainsRequest;
use Modules\Core\Http\Resources\Core\Tenancy\TenantDomains\TenantDomainsResource;
use Modules\Core\Models\TenantDomains;

class TenantDomainsController extends Controller
{
    use JsonResponseTrait;

    public function index(): JsonResponse
    {
        $items = $this->paginateQuery(
            TenantDomains::query(),
            15
        );

        return $this->paginatedResponse(
            TenantDomainsResource::collection($items),
            'Tenant domains retrieved successfully.'
        );
    }

    public function store(TenantDomainsRequest $request): JsonResponse
    {
        $item = TenantDomains::create(
            $request->validated()
        );

        return $this->createdResponse(
            new TenantDomainsResource($item),
            'Tenant domain created successfully.'
        );
    }

    public function show(TenantDomains $tenant_domain): JsonResponse
    {
        return $this->successResponse(
            new TenantDomainsResource($tenant_domain),
            'Tenant domain retrieved successfully.'
        );
    }

    public function update(
        TenantDomainsRequest $request,
        TenantDomains $tenant_domain
    ): JsonResponse {
        $tenant_domain->update(
            $request->validated()
        );

        return $this->successResponse(
            new TenantDomainsResource($tenant_domain),
            'Tenant domain updated successfully.'
        );
    }

    public function destroy(TenantDomains $tenant_domain): JsonResponse
    {
        $tenant_domain->delete();

        return $this->successResponse(
            null,
            'Tenant domain deleted successfully.'
        );
    }

    public function verify(Request $request): JsonResponse
    {
        return $this->notImplementedResponse(
            'verify not implemented'
        );
    }
}

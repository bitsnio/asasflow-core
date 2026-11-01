<?php

namespace Modules\Core\Http\Controllers\Core\Tenancy\Tenants;

use Bitsnio\AsasFlow\Foundation\Http\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Http\Requests\Core\Tenancy\Tenants\TenantsRequest;
use Modules\Core\Http\Resources\Core\Tenancy\Tenants\TenantsResource;
use Modules\Core\Models\Tenants;

class TenantsController extends Controller
{
    use JsonResponseTrait;

    public function index(): JsonResponse
    {
        $items = $this->paginateQuery(
            Tenants::query(),
            15
        );

        return $this->paginatedResponse(
            TenantsResource::collection($items),
            'Tenants retrieved successfully.'
        );
    }

    public function store(TenantsRequest $request): JsonResponse
    {
        $item = Tenants::create(
            $request->validated()
        );

        return $this->createdResponse(
            new TenantsResource($item),
            'Tenant created successfully.'
        );
    }

    public function show(Tenants $tenant): JsonResponse
    {
        return $this->successResponse(
            new TenantsResource($tenant),
            'Tenant retrieved successfully.'
        );
    }

    public function update(
        TenantsRequest $request,
        Tenants $tenant
    ): JsonResponse {
        $tenant->update(
            $request->validated()
        );

        return $this->successResponse(
            new TenantsResource($tenant),
            'Tenant updated successfully.'
        );
    }

    public function destroy(Tenants $tenant): JsonResponse
    {
        $tenant->delete();

        return $this->successResponse(
            null,
            'Tenant deleted successfully.'
        );
    }

    public function activate(Request $request): JsonResponse
    {
        $tenant = Tenants::findOrFail($request->input('id'));
        $tenant->update(['is_active' => true]);

        return $this->successResponse(
            new TenantsResource($tenant),
            'Tenant activated successfully.'
        );
    }

    public function suspend(Request $request): JsonResponse
    {
        $tenant = Tenants::findOrFail($request->input('id'));
        $tenant->update(['is_active' => false]);

        return $this->successResponse(
            new TenantsResource($tenant),
            'Tenant suspended successfully.'
        );
    }
}

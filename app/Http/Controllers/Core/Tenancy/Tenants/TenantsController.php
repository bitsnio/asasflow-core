<?php

namespace Modules\Core\Http\Controllers\Core\Tenancy\Tenants;

use Bitsnio\AsasFlow\Features\Tenancy\Models\Tenant;
use Bitsnio\AsasFlow\Foundation\Http\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Http\Requests\Core\Tenancy\Tenants\TenantsRequest;
use Modules\Core\Http\Resources\Core\Tenancy\Tenants\TenantsResource;

class TenantsController extends Controller
{
    use JsonResponseTrait;

    public function index(): JsonResponse
    {
        $items = $this->paginateQuery(
            Tenant::query(),
            15
        );

        return $this->paginatedResponse(
            TenantsResource::collection($items),
            'Tenants retrieved successfully.'
        );
    }

    public function store(TenantsRequest $request): JsonResponse
    {
        $item = Tenant::create(
            $request->validated()
        );

        return $this->createdResponse(
            new TenantsResource($item),
            'Tenant created successfully.'
        );
    }

    public function show($tenant): JsonResponse
    {
        $item = is_numeric($tenant)
            ? Tenant::findOrFail((int) $tenant)
            : Tenant::where('slug', $tenant)->firstOrFail();

        return $this->successResponse(
            new TenantsResource($item),
            'Tenant retrieved successfully.'
        );
    }

    public function update(
        TenantsRequest $request,
        $tenant
    ): JsonResponse {
        $item = is_numeric($tenant)
            ? Tenant::findOrFail((int) $tenant)
            : Tenant::where('slug', $tenant)->firstOrFail();

        $item->update(
            $request->validated()
        );

        return $this->successResponse(
            new TenantsResource($item),
            'Tenant updated successfully.'
        );
    }

    public function destroy($tenant): JsonResponse
    {
        $item = is_numeric($tenant)
            ? Tenant::findOrFail((int) $tenant)
            : Tenant::where('slug', $tenant)->firstOrFail();

        $item->delete();

        return $this->successResponse(
            null,
            'Tenant deleted successfully.'
        );
    }

    public function activate(Request $request): JsonResponse
    {
        $tenant = Tenant::findOrFail($request->input('id'));
        $tenant->update(['is_active' => true]);

        return $this->successResponse(
            new TenantsResource($tenant),
            'Tenant activated successfully.'
        );
    }

    public function suspend(Request $request): JsonResponse
    {
        $tenant = Tenant::findOrFail($request->input('id'));
        $tenant->update(['is_active' => false]);

        return $this->successResponse(
            new TenantsResource($tenant),
            'Tenant suspended successfully.'
        );
    }
}

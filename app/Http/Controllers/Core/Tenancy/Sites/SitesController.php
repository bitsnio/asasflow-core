<?php

namespace Modules\Core\Http\Controllers\Core\Tenancy\Sites;

use Bitsnio\AsasFlow\Foundation\Http\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Core\Http\Requests\Core\Tenancy\Sites\SitesRequest;
use Modules\Core\Http\Resources\Core\Tenancy\Sites\SitesResource;
use Modules\Core\Models\Sites;

class SitesController extends Controller
{
    use JsonResponseTrait;

    public function index(): JsonResponse
    {
        $items = $this->paginateQuery(
            Sites::query(),
            15
        );

        return $this->paginatedResponse(
            SitesResource::collection($items),
            'Sites retrieved successfully.'
        );
    }

    public function store(SitesRequest $request): JsonResponse
    {
        $item = Sites::create(
            $request->validated()
        );

        return $this->createdResponse(
            new SitesResource($item),
            'Site created successfully.'
        );
    }

    public function show(Sites $site): JsonResponse
    {
        return $this->successResponse(
            new SitesResource($site),
            'Site retrieved successfully.'
        );
    }

    public function update(
        SitesRequest $request,
        Sites $site
    ): JsonResponse {
        $site->update(
            $request->validated()
        );

        return $this->successResponse(
            new SitesResource($site),
            'Site updated successfully.'
        );
    }

    public function destroy(Sites $site): JsonResponse
    {
        $site->delete();

        return $this->successResponse(
            null,
            'Site deleted successfully.'
        );
    }
}

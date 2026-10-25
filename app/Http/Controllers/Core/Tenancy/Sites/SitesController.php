<?php

namespace Modules\Core\Http\Controllers\Core\Tenancy\Sites;

use Illuminate\Routing\Controller;
use Modules\Core\Http\Requests\Core\Tenancy\Sites\SitesRequest;
use Modules\Core\Http\Resources\Core\Tenancy\Sites\SitesResource;


class SitesController extends Controller
{

    public function index()
    {
        return SitesResource::collection(
            Sites::paginate(15)
        );
    }

    public function store(
        SitesRequest $request
    ) {
        $item = Sites::create(
            $request->validated()
        );

        return new SitesResource($item);
    }

    public function show(
        Sites $sites
    ) {
        return new SitesResource($sites);
    }

    public function update(
        SitesRequest $request,
        Sites $sites
    ) {
        $sites->update(
            $request->validated()
        );

        return new SitesResource($sites);
    }

    public function destroy(
        Sites $sites
    ) {
        $sites->delete();

        return response()->noContent();
    }
}

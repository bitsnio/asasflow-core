<?php

namespace Modules\Core\Http\Controllers\Core\Access\Permissions;

use Illuminate\Routing\Controller;
use Modules\Core\Http\Requests\Core\Access\Permissions\PermissionsRequest;
use Modules\Core\Models\Permissions;
use Modules\Core\Http\Resources\Core\Access\Permissions\PermissionsResource;


class PermissionsController extends Controller
{

    public function index()
    {
        return PermissionsResource::collection(
            Permissions::paginate(15)
        );
    }

    public function store(
        PermissionsRequest $request
    ) {
        $item = Permissions::create(
            $request->validated()
        );

        return new PermissionsResource($item);
    }

    public function show(
        Permissions $permissions
    ) {
        return new PermissionsResource($permissions);
    }

    public function update(
        PermissionsRequest $request,
        Permissions $permissions
    ) {
        $permissions->update(
            $request->validated()
        );

        return new PermissionsResource($permissions);
    }

    public function destroy(
        Permissions $permissions
    ) {
        $permissions->delete();

        return response()->noContent();
    }
}
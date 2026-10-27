<?php

namespace Modules\Core\Http\Controllers\Core\Access\Roles;

use Illuminate\Routing\Controller;
use Modules\Core\Http\Requests\Core\Access\Roles\RolesRequest;
use Modules\Core\Http\Resources\Core\Access\Roles\RolesResource;
use Illuminate\Http\Request;


class RolesController extends Controller
{

    public function index()
    {
        return RolesResource::collection(
            Roles::paginate(15)
        );
    }

    public function store(
        RolesRequest $request
    ) {
        $item = Roles::create(
            $request->validated()
        );

        return new RolesResource($item);
    }

    public function show(
        Roles $roles
    ) {
        return new RolesResource($roles);
    }

    public function update(
        RolesRequest $request,
        Roles $roles
    ) {
        $roles->update(
            $request->validated()
        );

        return new RolesResource($roles);
    }

    public function destroy(
        Roles $roles
    ) {
        $roles->delete();

        return response()->noContent();
    }

    public function assignPermissions(Request $request)
    {
        return response()->json([
            'message' => 'assignPermissions not implemented',
        ], 501);
    }
}
<?php

namespace Modules\Core\Http\Controllers\Core\Access\Roles;

use Bitsnio\AsasFlow\Facades\Permission;
use Bitsnio\AsasFlow\Foundation\Http\Traits\JsonResponseTrait;
use Bitsnio\AsasFlow\Foundation\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Http\Resources\Core\Access\Roles\RolesResource;
use Throwable;

class RolesController extends Controller
{
    use JsonResponseTrait;

    public function index(): JsonResponse
    {
        $items = $this->paginateQuery(
            Role::with('permissions'),
            15
        );

        return $this->paginatedResponse(
            RolesResource::collection($items),
            'Roles retrieved successfully.'
        );
    }

    public function store(Request $request): JsonResponse
    {
        // Support module-based role creation (with modules, permissions, except) or standard role creation
        $roleName = $request->input('role_name', $request->input('name'));

        $validator = Validator::make(
            array_merge($request->all(), ['name' => $roleName]),
            [
                'name' => ['required', 'string', 'max:125'],
                'description' => ['nullable', 'string', 'max:1000'],
                'modules' => ['nullable'],
                'permissions' => ['nullable', 'array'],
                'except' => ['nullable', 'array'],
                'is_active' => ['nullable', 'boolean'],
                'tenant_id' => ['nullable', 'integer'],
            ]
        );

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        try {
            if ($request->filled('modules')) {
                $result = Permission::createRoleWithModuleBasedPermissions(
                    roleName: $roleName,
                    roleDescription: $request->input('description'),
                    moduleNames: $request->input('modules'),
                    permissionNames: $request->input('permissions', []),
                    exceptPermissions: $request->input('except', []),
                );

                return $this->createdResponse(
                    $result,
                    'Role created with module permissions successfully.'
                );
            }

            $role = Role::updateOrCreate(
                [
                    'name' => $roleName,
                    'guard_name' => $request->input('guard_name', 'api'),
                ],
                [
                    'tenant_id' => $request->input('tenant_id'),
                    'description' => $request->input('description'),
                    'is_active' => $request->boolean('is_active', true),
                ]
            );

            if ($request->filled('permissions')) {
                Permission::assignPermissionsToRole($role->id, $request->input('permissions'), true);
            }

            return $this->createdResponse(
                new RolesResource($role->load('permissions')),
                'Role created successfully.'
            );
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function show($role): JsonResponse
    {
        $roleModel = is_numeric($role)
            ? Role::with('permissions')->findOrFail((int) $role)
            : Role::with('permissions')->where('name', $role)->firstOrFail();

        return $this->successResponse(
            [
                'id' => $roleModel->id,
                'name' => $roleModel->name,
                'description' => $roleModel->description,
                'is_active' => $roleModel->is_active,
                'permissions' => $roleModel->permissions->map(fn ($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                ])->values(),
            ],
            'Role retrieved successfully.'
        );
    }

    public function update(Request $request, $role): JsonResponse
    {
        $roleModel = is_numeric($role)
            ? Role::findOrFail((int) $role)
            : Role::where('name', $role)->firstOrFail();

        $roleModel->update($request->only(['name', 'description', 'is_active', 'tenant_id']));

        if ($request->has('permissions') && is_array($request->input('permissions'))) {
            Permission::updateRolePermissions($roleModel->id, $request->input('permissions'));
        }

        return $this->successResponse(
            new RolesResource($roleModel->load('permissions')),
            'Role updated successfully.'
        );
    }

    public function destroy($role): JsonResponse
    {
        $roleModel = is_numeric($role)
            ? Role::findOrFail((int) $role)
            : Role::where('name', $role)->firstOrFail();

        $roleModel->delete();

        return $this->successResponse(
            null,
            "Role [{$roleModel->name}] deleted successfully."
        );
    }

    public function assignPermissions(Request $request): JsonResponse
    {
        $roleIdentifier = $request->input('role_id', $request->input('role'));

        $validator = Validator::make(
            array_merge($request->all(), ['role' => $roleIdentifier]),
            [
                'role' => ['required'],
                'permissions' => ['required', 'array'],
                'mode' => ['nullable', 'string', 'in:sync,add,revoke'],
            ]
        );

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        try {
            $mode = $request->input('mode', 'add');
            $permissions = $request->input('permissions', []);

            if ($mode === 'add') {
                $result = Permission::addPermissionsToRole($roleIdentifier, $permissions);

                return $this->successResponse(
                    $result,
                    'Permissions added to role successfully.'
                );
            }

            if ($mode === 'revoke') {
                $result = Permission::revokePermissionsFromRole($roleIdentifier, $permissions);

                return $this->successResponse(
                    $result,
                    'Permissions revoked from role successfully.'
                );
            }

            $updatedRole = Permission::assignPermissionsToRole($roleIdentifier, $permissions, true);

            return $this->successResponse(
                [
                    'role' => $updatedRole->name,
                    'permissions' => $updatedRole->permissions()->pluck('name')->values(),
                ],
                'Role permissions synced successfully.'
            );
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}

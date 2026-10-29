<?php

namespace Modules\Core\Http\Controllers\Core\Access\Roles;

use Bitsnio\AsasFlow\Facades\Permission;
use Bitsnio\AsasFlow\Foundation\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Http\Resources\Core\Access\Roles\RolesResource;
use Throwable;

class RolesController extends Controller
{
    public function index()
    {
        return RolesResource::collection(
            Role::with('permissions')->paginate(15)
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
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
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

                return response()->json([
                    'success' => true,
                    'message' => 'Role created with module permissions successfully.',
                    'data' => $result,
                ], 201);
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

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully.',
                'data' => new RolesResource($role->load('permissions')),
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function show($role): JsonResponse
    {
        $roleModel = is_numeric($role)
            ? Role::with('permissions')->findOrFail((int) $role)
            : Role::with('permissions')->where('name', $role)->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $roleModel->id,
                'name' => $roleModel->name,
                'description' => $roleModel->description,
                'is_active' => $roleModel->is_active,
                'permissions' => $roleModel->permissions->map(fn($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                ])->values(),
            ],
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully.',
            'data' => new RolesResource($roleModel->load('permissions')),
        ]);
    }

    public function destroy($role): JsonResponse
    {
        $roleModel = is_numeric($role)
            ? Role::findOrFail((int) $role)
            : Role::where('name', $role)->firstOrFail();

        $roleModel->delete();

        return response()->json([
            'success' => true,
            'message' => "Role [{$roleModel->name}] deleted successfully.",
        ]);
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
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $mode = $request->input('mode', 'add');
            $permissions = $request->input('permissions', []);

            if ($mode === 'add') {
                $result = Permission::addPermissionsToRole($roleIdentifier, $permissions);
                return response()->json([
                    'success' => true,
                    'message' => 'Permissions added to role successfully.',
                    'data' => $result,
                ]);
            }

            if ($mode === 'revoke') {
                $result = Permission::revokePermissionsFromRole($roleIdentifier, $permissions);
                return response()->json([
                    'success' => true,
                    'message' => 'Permissions revoked from role successfully.',
                    'data' => $result,
                ]);
            }

            $updatedRole = Permission::assignPermissionsToRole($roleIdentifier, $permissions, true);

            return response()->json([
                'success' => true,
                'message' => 'Role permissions synced successfully.',
                'data' => [
                    'role' => $updatedRole->name,
                    'permissions' => $updatedRole->permissions()->pluck('name')->values(),
                ],
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}

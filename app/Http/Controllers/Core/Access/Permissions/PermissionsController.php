<?php

namespace Modules\Core\Http\Controllers\Core\Access\Permissions;

use Bitsnio\AsasFlow\Facades\Permission;
use Bitsnio\AsasFlow\Foundation\Models\Permission as PermissionModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PermissionsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $module = $request->query('module');
        $labelValue = $request->boolean('label_value', false);

        if ($request->boolean('sync', false)) {
            Permission::syncPermissions($module);
        }

        $permissions = Permission::getAllPermissions($module, $labelValue);

        return response()->json([
            'success' => true,
            'data' => $permissions,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'guard_name' => ['nullable', 'string', 'max:50'],
        ]);

        $item = PermissionModel::updateOrCreate(
            [
                'name' => $validated['name'],
                'guard_name' => $validated['guard_name'] ?? 'api',
            ],
            [
                'description' => $validated['description'] ?? $validated['name'],
            ]
        );

        Permission::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Permission saved successfully.',
            'data' => $item,
        ], 201);
    }

    public function show($permission): JsonResponse
    {
        $item = is_numeric($permission)
            ? PermissionModel::findOrFail((int) $permission)
            : PermissionModel::where('name', $permission)->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $item,
        ]);
    }

    public function update(Request $request, $permission): JsonResponse
    {
        $item = is_numeric($permission)
            ? PermissionModel::findOrFail((int) $permission)
            : PermissionModel::where('name', $permission)->firstOrFail();

        $item->update($request->only(['name', 'description', 'guard_name']));
        Permission::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully.',
            'data' => $item,
        ]);
    }

    public function destroy($permission): JsonResponse
    {
        $item = is_numeric($permission)
            ? PermissionModel::findOrFail((int) $permission)
            : PermissionModel::where('name', $permission)->firstOrFail();

        $item->delete();
        Permission::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully.',
        ]);
    }
}

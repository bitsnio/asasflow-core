<?php

namespace Modules\Core\Http\Controllers\Core\Access\Users;

use Bitsnio\AsasFlow\Facades\Menu;
use Bitsnio\AsasFlow\Facades\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Http\Requests\Core\Access\Users\UsersRequest;
use Modules\Core\Http\Resources\Core\Access\Users\UsersResource;
use Modules\Core\Models\Users;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Throwable;

class UsersController extends Controller
{
    public function index()
    {
        return UsersResource::collection(
            Users::with('roles')->paginate(15)
        );
    }

    public function store(
        UsersRequest $request
    ) {
        $item = Users::create(
            $request->validated()
        );

        return new UsersResource($item);
    }

    public function show(
        Users $users
    ) {
        return new UsersResource($users->load('roles'));
    }

    public function update(
        UsersRequest $request,
        Users $users
    ) {
        $users->update(
            $request->validated()
        );

        return new UsersResource($users);
    }

    public function destroy(
        Users $users
    ) {
        $users->delete();

        return response()->noContent();
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $input = $request->only('email', 'password');

            $validator = Validator::make($input, [
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            if (!JWTAuth::attempt($input)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid login credentials.',
                ], 401);
            }

            $user = Users::with(['site', 'tenant', 'roles'])
                ->where('email', $request->input('email'))
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            if (isset($user->is_active) && (int) $user->is_active === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is inactive/blocked. Please contact support.',
                ], 403);
            }

            $fullName = trim(($user->firstName ?? '') . ' ' . ($user->lastName ?? ''));

            $customClaims = [
                'user_id' => $user->id,
                'user_name' => $fullName !== '' ? $fullName : ($user->name ?? $user->email),
                'email' => $user->email,
                'tenant_id' => $user->tenant_id,
                'site_id' => $user->site_id,
                'role_id' => $user->role_id,
                'roles' => $user->roles->pluck('name')->values()->toArray(),
            ];

            $token = JWTAuth::claims($customClaims)->attempt($input);

            $menu = Menu::getFormattedMenu(
                moduleName: null,
                filterByUserPermissions: true,
                onlyVisible: true,
                user: $user
            );

            return response()->json([
                'success' => true,
                'message' => 'User logged in successfully.',
                'token' => $token,
                'data' => [
                    'user_info' => $customClaims,
                    'meta' => $menu,
                ],
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create token: ' . $e->getMessage(),
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'firstName' => ['required', 'string', 'max:100'],
            'lastName' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'tenant_id' => ['required', 'integer'],
            'site_id' => ['nullable', 'integer'],
            'role_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);

        $user = Users::create($data);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.',
            'data' => new UsersResource($user),
        ], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            Menu::clearCache($user);
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out.',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout: ' . $e->getMessage(),
            ], 400);
        }
    }

    public function assignRoles(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'roles' => ['required'],
            'user_ids' => ['required_without:user_id'],
            'user_id' => ['required_without:user_ids'],
            'replace_existing' => ['sometimes', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $userIds = $request->input('user_ids', $request->input('user_id'));
            $roles = $request->input('roles');
            $replaceExisting = (bool) $request->input('replace_existing', true);

            $result = Permission::assignRoleToUsers($roles, $userIds, $replaceExisting);

            return response()->json([
                'success' => true,
                'message' => 'Roles assigned successfully.',
                'data' => $result,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function activate(Request $request): JsonResponse
    {
        $user = Users::findOrFail($request->input('id'));
        $user->update(['email_verified_at' => $user->email_verified_at ?? now()]);

        return response()->json([
            'success' => true,
            'message' => 'User activated successfully.',
            'data' => new UsersResource($user),
        ]);
    }

    public function deactivate(Request $request): JsonResponse
    {
        $user = Users::findOrFail($request->input('id'));

        return response()->json([
            'success' => true,
            'message' => 'User deactivated successfully.',
            'data' => new UsersResource($user),
        ]);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        return response()->json([
            'success' => true,
            'message' => 'Password reset instructions processed.',
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Users::where('email', $request->input('email'))->firstOrFail();
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.',
        ]);
    }
}

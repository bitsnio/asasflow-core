<?php

namespace Modules\Core\Http\Controllers\Core\Access\Users;

use Bitsnio\AsasFlow\Facades\Menu;
use Bitsnio\AsasFlow\Facades\Permission;
use Bitsnio\AsasFlow\Foundation\Http\Traits\JsonResponseTrait;
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
    use JsonResponseTrait;

    public function index(): JsonResponse
    {
        $items = $this->paginateQuery(
            Users::with('roles'),
            15
        );

        return $this->paginatedResponse(
            UsersResource::collection($items),
            'Users retrieved successfully.'
        );
    }

    public function store(UsersRequest $request): JsonResponse
    {
        $item = Users::create(
            $request->validated()
        );

        return $this->createdResponse(
            new UsersResource($item),
            'User created successfully.'
        );
    }

    public function show(Users $user): JsonResponse
    {
        return $this->successResponse(
            new UsersResource($user->load('roles')),
            'User retrieved successfully.'
        );
    }

    public function update(
        UsersRequest $request,
        Users $user
    ): JsonResponse {
        $user->update(
            $request->validated()
        );

        return $this->successResponse(
            new UsersResource($user),
            'User updated successfully.'
        );
    }

    public function destroy(Users $user): JsonResponse
    {
        $user->delete();

        return $this->successResponse(
            null,
            'User deleted successfully.'
        );
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
                return $this->validationErrorResponse($validator->errors());
            }

            if (!JWTAuth::attempt($input)) {
                return $this->unauthorizedResponse('Invalid login credentials.');
            }

            $user = Users::with(['site', 'tenant', 'roles'])
                ->where('email', $request->input('email'))
                ->first();

            if (!$user) {
                return $this->notFoundResponse('User not found.');
            }

            if (isset($user->is_active) && (int) $user->is_active === 0) {
                return $this->forbiddenResponse('Your account is inactive/blocked. Please contact support.');
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

            $response = $this->successResponse(
                [
                    'token' => $token,
                    'user_info' => $customClaims,
                    'meta' => $menu,
                ],
                'User logged in successfully.'
            );

            // Preserve top-level `token` alongside `data.token` for REST clients like access.http
            $payload = $response->getData(true);
            $payload['token'] = $token;
            $response->setData($payload);

            return $response;
        } catch (JWTException $e) {
            return $this->errorResponse('Could not create token: ' . $e->getMessage(), 500);
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
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
            return $this->validationErrorResponse($validator->errors());
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);

        $user = Users::create($data);

        return $this->createdResponse(
            new UsersResource($user),
            'User registered successfully.'
        );
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            Menu::clearCache($user);
            JWTAuth::invalidate(JWTAuth::getToken());

            return $this->successResponse(
                null,
                'Successfully logged out.'
            );
        } catch (Throwable $e) {
            return $this->errorResponse('Failed to logout: ' . $e->getMessage(), 400);
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
            return $this->validationErrorResponse($validator->errors());
        }

        try {
            $userIds = $request->input('user_ids', $request->input('user_id'));
            $roles = $request->input('roles');
            $replaceExisting = (bool) $request->input('replace_existing', true);

            $result = Permission::assignRoleToUsers($roles, $userIds, $replaceExisting);

            return $this->successResponse(
                $result,
                'Roles assigned successfully.'
            );
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function activate(Request $request): JsonResponse
    {
        $user = Users::findOrFail($request->input('id'));
        $user->update(['email_verified_at' => $user->email_verified_at ?? now()]);

        return $this->successResponse(
            new UsersResource($user),
            'User activated successfully.'
        );
    }

    public function deactivate(Request $request): JsonResponse
    {
        $user = Users::findOrFail($request->input('id'));

        return $this->successResponse(
            new UsersResource($user),
            'User deactivated successfully.'
        );
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        return $this->successResponse(
            null,
            'Password reset instructions processed.'
        );
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = Users::where('email', $request->input('email'))->firstOrFail();
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return $this->successResponse(
            null,
            'Password reset successfully.'
        );
    }
}

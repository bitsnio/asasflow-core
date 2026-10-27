<?php

namespace Modules\Core\Http\Controllers\Core\Access\Users;

use Illuminate\Routing\Controller;
use Modules\Core\Http\Requests\Core\Access\Users\UsersRequest;
use Modules\Core\Models\Users;
use Modules\Core\Http\Resources\Core\Access\Users\UsersResource;
use Illuminate\Http\Request;


class UsersController extends Controller
{

    public function index()
    {
        return UsersResource::collection(
            Users::paginate(15)
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
        return new UsersResource($users);
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

    public function activate(Request $request)
    {
        return response()->json([
            'message' => 'activate not implemented',
        ], 501);
    }

    public function deactivate(Request $request)
    {
        return response()->json([
            'message' => 'deactivate not implemented',
        ], 501);
    }

    public function assignRoles(Request $request)
    {
        return response()->json([
            'message' => 'assignRoles not implemented',
        ], 501);
    }

    public function login(Request $request)
    {
        return response()->json([
            'message' => 'login not implemented',
        ], 501);
    }

    public function logout(Request $request)
    {
        return response()->json([
            'message' => 'logout not implemented',
        ], 501);
    }

    public function register(Request $request)
    {
        return response()->json([
            'message' => 'register not implemented',
        ], 501);
    }

    public function forgotPassword(Request $request)
    {
        return response()->json([
            'message' => 'forgotPassword not implemented',
        ], 501);
    }

    public function resetPassword(Request $request)
    {
        return response()->json([
            'message' => 'resetPassword not implemented',
        ], 501);
    }
}
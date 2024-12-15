<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Services\UserService;
use Illuminate\Http\{JsonResponse, Request};

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): JsonResponse
    {
        $params = [
            'per_page' => $request->perPage ?? 10,
            'page'     => $request->page ?? 1,
        ];

        $users = $this->userService->getAllUsers($params);

        return response()->json($users, 200);
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = $this->userService->createUser($data);

        return response()->json(['status' => 'success', 'data' => $user], 201);
    }

    public function show(string $id)
    {
        $user = $this->userService->getUser($id);

        return response()->json($user, 200);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}

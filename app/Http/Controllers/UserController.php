<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $query = User::query();

        if ($request->has('email')) {
            $query->filterByEmail($request->input('email'));
        }

        $users = $query->paginate($perPage);

        return $this->sendSuccess([
            'data' => UserResource::collection($users),
            'pagination' => [
                'total' => $users->total(),
                'count' => $users->count(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'total_pages' => $users->lastPage(),
            ],
        ], 'Users retrieved successfully');
    }

    public function show(User $user)
    {
        return $this->sendSuccess(new UserResource($user), 'User retrieved successfully');
    }

    public function store(UserRequest $request)
    {
        $data = $this->userService->create($request->validated());
        return $this->sendSuccess([
            'user' => new UserResource($data['user']),
            'token' => $data['token']
        ], 'User created successfully');    }

    public function update(UserRequest $request, User $user)
    {
//        if (auth()->id() !== $user->id && !auth()->user()->isAdmin()) {
//            return $this->sendError('Unauthorized', 403);
//        }
        $user=User::find($user->id);
        $updatedUser = $this->userService->update($user, $request->validated());
        return $this->sendSuccess(new UserResource($updatedUser), 'User updated successfully');
    }

    public function destroy(User $user)
    {
        if (auth()->id() !== $user->id && !auth()->user()->isAdmin()) {
            return $this->sendError('Unauthorized', 403);
        }

        $this->userService->delete($user);
        return $this->sendSuccess([], 'User deleted successfully');
    }

}

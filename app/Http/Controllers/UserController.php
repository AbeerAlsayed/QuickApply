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

    // عرض بيانات مستخدم معين
    public function show(User $user)
    {
        return $this->sendSuccess(new UserResource($user), 'User retrieved successfully');
    }

    // إنشاء مستخدم جديد
    public function store(UserRequest $request)
    {
        $user = $this->userService->create($request->validated());
        return $this->sendSuccess(new UserResource($user), 'User created successfully');
    }

    // تحديث بيانات المستخدم مع التحقق من الصلاحيات
    public function update(UserRequest $request, User $user)
    {
//        if (auth()->id() !== $user->id && !auth()->user()->isAdmin()) {
//            return $this->sendError('Unauthorized', 403);
//        }
        $user=User::find($user->id);
        $updatedUser = $this->userService->update($user, $request->validated());
        return $this->sendSuccess(new UserResource($updatedUser), 'User updated successfully');
    }

    // حذف مستخدم مع التحقق من الصلاحيات
    public function destroy(User $user)
    {
        if (auth()->id() !== $user->id && !auth()->user()->isAdmin()) {
            return $this->sendError('Unauthorized', 403);
        }

        $this->userService->delete($user);
        return $this->sendSuccess([], 'User deleted successfully');
    }

    public function getUserCompaniesWithPositions($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $companies = $user->companies()->with('positions')->get();

        $results = $companies->map(function ($company) {
            return [
                'company_id' => $company->id,
                'company_name' => $company->name,
                'company_email' => $company->email,
                'positions' => $company->positions->map(function ($position) {
                    return [
                        'position_id' => $position->id,
                        'position_title' => $position->title,
                    ];
                }),
                'is_sent' => $company->pivot->is_sent,
            ];
        });

        return response()->json($results);
    }

}

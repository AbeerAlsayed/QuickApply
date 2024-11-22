<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;

class UserController extends BaseController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // عرض جميع المستخدمين
    public function index()
    {
        $users = User::all();
        return $this->sendSuccess(UserResource::collection($users), 'Users retrieved successfully');
    }

    // عرض مستخدم معين
    public function show(User $user)
    {
        return $this->sendSuccess(new UserResource($user), 'User retrieved successfully');
    }

    // إنشاء مستخدم جديد
    public function store(UserRequest $request)
    {
        // التفويض للـ Service لمعالجة العملية
        $user = $this->userService->create($request->validated());
        return $this->sendSuccess(new UserResource($user), 'User created successfully');
    }

    // تحديث بيانات مستخدم
    public function update(UserRequest $request, User $user)
    {
        // التفويض للـ Service لمعالجة العملية
        $updatedUser = $this->userService->update($user, $request->validated());
        return $this->sendSuccess(new UserResource($updatedUser), 'User updated successfully');
    }

    // حذف مستخدم
    public function destroy(User $user)
    {
        // التفويض للـ Service لمعالجة العملية
        $this->userService->delete($user);
        return $this->sendSuccess([], 'User deleted successfully');
    }
}
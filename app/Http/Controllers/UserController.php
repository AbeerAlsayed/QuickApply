<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // عرض جميع المستخدمين
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // العدد الافتراضي 10 إذا لم يتم تمرير 'per_page'
        $users = User::paginate($perPage);

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

    public function filter(Request $request)
    {
        // اجلب الإيميل من الطلب
        $email = $request->query('email');

        // فلتر المستخدمين باستخدام Scope
        $users = User::filterByEmail($email)->get();

        // أعد قائمة المستخدمين
        return  UserResource::collection($users);

    }
}

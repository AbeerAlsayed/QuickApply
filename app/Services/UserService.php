<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return User::create($data);
        });
    }

    public function update(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            $user->update($data);
            return $user;
        });
    }

    public function delete(User $user)
    {
        return DB::transaction(function () use ($user) {
            return $user->delete();
        });
    }
}

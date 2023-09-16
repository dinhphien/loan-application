<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\User;

class UserService
{
    public function create(string $username, string $password, bool $isAdmin): User
    {
        return User::create([
            'username' => $username,
            'password' => $password,
            'is_admin' => $isAdmin,
        ]);
    }
}

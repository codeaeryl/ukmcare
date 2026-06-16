<?php

namespace App\Factories;

use App\Models\User;
use App\Enums\Role;
use Illuminate\Support\Facades\Hash;

class DefaultUserFactory implements UserFactoryInterface
{
    protected Role $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $this->role,
        ]);
    }
}

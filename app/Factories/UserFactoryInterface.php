<?php

namespace App\Factories;

use App\Models\User;

interface UserFactoryInterface
{
    /**
     * Create a user and any associated profile records.
     *
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User;
}

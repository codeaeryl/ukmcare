<?php

namespace App\Factories;

use App\Enums\Role;

class UserFactory
{
    /**
     * Factory method to get the appropriate user creator.
     *
     * @param Role $role
     * @return UserFactoryInterface
     */
    public static function make(Role $role): UserFactoryInterface
    {
        return match ($role) {
            Role::PATIENT => new PatientFactory(),
            Role::DOCTOR => new DoctorFactory(),
            default => new DefaultUserFactory($role),
        };
    }
}

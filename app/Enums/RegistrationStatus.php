<?php

namespace App\Enums;

enum RegistrationStatus: string
{
    case REGISTERED = 'registered';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
}

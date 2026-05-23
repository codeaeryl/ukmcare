<?php

namespace App\Enums;

enum RegistrationStatus: string
{
    case REGISTERED = 'registered';
    case CANCELLED = 'cancelled';
    case PENDING = 'pending';
    case COMPLETED = 'completed';
}

<?php

namespace App\Enums;

enum BillStatus: string
{
    case PENDING = 'pending';
    case COMPLETE = 'complete';
    case CANCELLED = 'cancelled';
}

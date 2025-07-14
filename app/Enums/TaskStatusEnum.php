<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Canceled = 'canceled';
}

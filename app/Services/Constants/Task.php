<?php

namespace App\Services\Constants;

class Task
{
    public const IN_WORK_STATUS   = 'in_work';
    public const COMPLETED_STATUS = 'completed';
    public const STATUSES         = [
        self::IN_WORK_STATUS,
        self::COMPLETED_STATUS,
    ];
    public const HIGH_PRIORITY    = 'high';
    public const MIDDLE_PRIORITY  = 'middle';
    public const LOW_PRIORITY     = 'low';
    public const PRIORITIES       = [
        self::HIGH_PRIORITY,
        self::MIDDLE_PRIORITY,
        self::LOW_PRIORITY,
    ];
}

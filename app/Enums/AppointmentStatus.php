<?php

namespace App\Enums;

class AppointmentStatus
{
    const SCHEDULED = 1;
    const CONFIRMED = 2;
    const CANCELLED = 3;

    public static array $statuses = [
        self::SCHEDULED => 'SCHEDULED',
        self::CONFIRMED => 'CONFIRMED',
        self::CANCELLED => 'CANCELLED',
    ];

    public static array $colors = [
        self::SCHEDULED => 'primary',
        self::CONFIRMED => 'success',
        self::CANCELLED => 'danger',
    ];

    public static function getStatusBy($id)
    {
        return self::$statuses[$id] ?? 'undefined';
    }

    public static function getColorBy($id)
    {
        return self::$colors[$id] ?? 'primary';
    }
}

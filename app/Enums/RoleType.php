<?php

namespace App\Enums;
/**
 * Seems my storm doesn't understand enums, or I dont now how it force.
 * Class RoleType
 * @package App\Enums
 */
class RoleType {

    const OWNER = 0;
    const ADMIN = 1;
    const USER  = 2;

    public static array $roles = [
        0 => 'OWNER',
        1 => 'ADMIN',
        2 => 'USER',
    ];

    public static function getRoleBy($id)
    {
        return self::$roles[$id] ?? 'undefined';
    }
}

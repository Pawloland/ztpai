<?php

namespace App\Enum;


enum Permissions: int
{
    case RemoveMovie = 5;
    case RemoveReservation = 6;
    case RemoveScreening = 7;
    case RemoveClient = 8;
    case RemoveWorker = 9;

    //https://stackoverflow.com/a/79460196/14030373
    public static function tryFromName(string $name): ?Permissions
    {
        /** @var ?array<non-empty-string, static> $cache */
        static $cache;

        $cache ??= array_column(Permissions::cases(), null, 'name');

        return $cache[$name] ?? null;
    }

}
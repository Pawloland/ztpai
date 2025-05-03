<?php

namespace App\Enum;

use App\Entity\ClientSessions;
use App\Entity\WorkerSessions;


enum SessionVariant: string
{
    case CLIENT = 'c';
    case WORKER = 'w';

    /** @return class-string */
    public function ENTITY(): string
    {
        return match ($this) {
            self::CLIENT => ClientSessions::class,
            self::WORKER => WorkerSessions::class,
        };
    }

    public function IDENTIFIER(): string
    {
        return match ($this) {
            self::CLIENT => 'email',
            self::WORKER => 'nick',
        };
    }

    public function COLUMN(): string
    {
        return match ($this) {
            self::CLIENT => 'id_client',
            self::WORKER => 'id_worker',
        };
    }

    public function TABLE(): string
    {
        return match ($this) {
            self::CLIENT => 'client',
            self::WORKER => 'worker',
        };
    }
}
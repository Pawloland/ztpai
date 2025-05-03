<?php

namespace App\Enum;
enum CookieVariant
{
    case CLIENT;
    case WORKER;

    public function HTTPONLY(): string
    {
        return match ($this) {
            self::CLIENT => 'auth_httponly',
            self::WORKER => 'auth_worker_httponly',
        };
    }


    public function NOTHTTPONLY(): string
    {
        return match ($this) {
            self::CLIENT => 'auth',
            self::WORKER => 'auth_worker',
        };
    }

    public function NOTHTTPONLY_IDENTIFIER(): string
    {
        return match ($this) {
            self::CLIENT => 'email',
            self::WORKER => 'nick',
        };
    }
}

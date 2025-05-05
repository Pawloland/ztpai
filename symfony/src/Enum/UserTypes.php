<?php

namespace App\Enum;

enum UserTypes: string
{
    case GUEST = "GUEST";
    case CLIENT = "CLIENT";
    case WORKER = "WORKER";
}
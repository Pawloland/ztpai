<?php

namespace App\Enum;


enum Permissions: int
{
    case RemoveMovie = 5;
    case RemoveReservation = 6;
    case RemoveScreening = 7;
    case RemoveClient = 8;
    case RemoveWorker = 9;
}
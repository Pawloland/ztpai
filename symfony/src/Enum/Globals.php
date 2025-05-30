<?php

namespace App\Enum;

class Globals
{
    /*
     * Used by custom authenticator and voter
     */
    public const string AUTHENTICATED_ENTITIES = 'AUTHENTICATED_ENTITIES';
    public const string AUTHORIZED_ENTITY = 'AUTHORIZED_ENTITY';

    public const string RABBITMQ_QUEUE = 'email_queue';
    public const string RABBITMQ_QUEUE_ACK = 'email_queue_ack';
}
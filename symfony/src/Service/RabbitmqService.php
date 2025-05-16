<?php

namespace App\Service;

use App\Enum\Globals;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitmqService
{
    private static ?AMQPChannel $channel = null;

    private static function getChannel(): AMQPChannel
    {
        if (self::$channel === null) {
            self::$channel = new AMQPStreamConnection(
                'rabbitmq',
                getenv('RABBITMQ_NODE_PORT') ?: 5672,
                getenv('RABBITMQ_DEFAULT_USER') ?: 'guest',
                getenv('RABBITMQ_DEFAULT_PASS') ?: 'guest'
            )->channel();
        }
        return self::$channel;
    }

    public static function requestConfirmationEmail(string $email): void
    {
        $channel = self::getChannel();
        $channel->queue_declare(Globals::RABBITMQ_QUEUE, false, true, false, false);
        $channel->basic_publish(new AMQPMessage($email), '', Globals::RABBITMQ_QUEUE);
    }
}
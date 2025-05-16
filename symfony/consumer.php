<?php

require __DIR__ . '/vendor/autoload.php';

use App\Enum\Globals;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection(
    'rabbitmq',
    getenv('RABBITMQ_NODE_PORT') ?: 5672,
    getenv('RABBITMQ_DEFAULT_USER') ?: 'guest',
    getenv('RABBITMQ_DEFAULT_PASS') ?: 'guest'
);

$channel = $connection->channel();

$channel->queue_declare(Globals::RABBITMQ_QUEUE, false, false, false, false);

echo "Waiting for messages\n";

$callback = function ($msg) {
    echo "Confirmation email send to '{$msg->body}'\n";
};

$channel->basic_consume(Globals::RABBITMQ_QUEUE, '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
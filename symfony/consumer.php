<?php

require __DIR__ . '/vendor/autoload.php';

use App\Enum\Globals;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection(
    'rabbitmq',
    getenv('RABBITMQ_NODE_PORT') ?: 5672,
    getenv('RABBITMQ_DEFAULT_USER') ?: 'guest',
    getenv('RABBITMQ_DEFAULT_PASS') ?: 'guest'
);

$channel = $connection->channel();
$confirmationChannel = $connection->channel();

$channel->queue_declare(Globals::RABBITMQ_QUEUE, false, true, false, false);
$confirmationChannel->queue_declare(Globals::RABBITMQ_QUEUE_ACK, false, true, false, false);

echo "Waiting for messages\n";

$callback = function (AMQPMessage $msg) use ($channel, $confirmationChannel) {
    $mail = $msg->getBody();
    echo "Confirmation email send to '{$mail}'\n";
    // wait a bit to simulate sending email and confirming it by a client
    sleep(60);
    // send acknowledgment to the confirmation channel, which will be consumed by the symfony command
    echo "Client confirmed email, sending ack to confirmation channel\n";
    $confirmationChannel->basic_publish(new AMQPMessage($mail), '', Globals::RABBITMQ_QUEUE_ACK);
    // send acknowledgment to RabbitMQ, this doesn't send anything to the message producer
    $channel->basic_ack($msg->getDeliveryTag());
};

$channel->basic_consume(Globals::RABBITMQ_QUEUE, '', false, false, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$confirmationChannel->close();
$connection->close();

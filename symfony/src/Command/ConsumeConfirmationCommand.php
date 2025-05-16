<?php

namespace App\Command;

use App\Entity\Client;
use App\Enum\Globals;
use Doctrine\ORM\EntityManagerInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:consume-confirmation',
    description: 'Listens for ack from consumer.php that a client has confirmed the mail, and updated the database accordingly.',
)]
class ConsumeConfirmationCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = new AMQPStreamConnection(
            'rabbitmq',
            getenv('RABBITMQ_NODE_PORT') ?: 5672,
            getenv('RABBITMQ_DEFAULT_USER') ?: 'guest',
            getenv('RABBITMQ_DEFAULT_PASS') ?: 'guest'
        );
        $channel = $connection->channel();


        $channel->queue_declare(Globals::RABBITMQ_QUEUE_ACK, false, true, false, false);

        $output->writeln('Waiting for ack messages');

        $callback = function (AMQPMessage $msg) use ($output) {
            $mail = $msg->getBody();

            $client = $this->em->getRepository(Client::class)->findOneBy(['mail' => $mail]);
            if ($client) {
                $client->setMailConfirmed(true);
                $this->em->persist($client);
                $this->em->flush();
                $this->em->refresh($client);

                $output->writeln("Client with ID={$client->getIdClient()} confirmed mail '{$mail}'.");
            } else {
                $output->writeln("Client with mail='{$mail}' not found.");
            }
        };

        $channel->basic_consume(Globals::RABBITMQ_QUEUE_ACK, '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        return Command::SUCCESS;
    }
}

<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// create rabbitmq connection producer
$connection = new AMQPStreamConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASS);
$channel = $connection->channel();

$channel->queue_declare(RABBITMQ_QUEUE, false, false, false, false);

$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg, RABBITMQ_EXCHANGE, RABBITMQ_ROUTING_KEY);

echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();

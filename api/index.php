<?php
set_time_limit(0);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
ob_end_flush();

$connection = new AMQPStreamConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASS);
$channel = $connection->channel();

$channel->queue_declare(RABBITMQ_QUEUE, false, false, false, false);

while (true) {
  $msg = $channel->basic_get(RABBITMQ_QUEUE, true);
  if ($msg) {
    echo "event: message" . PHP_EOL;
    echo "data: " . $msg->body . PHP_EOL . PHP_EOL;
    flush();
  }

  if (connection_aborted()) break;
}

$channel->close();
$connection->close();

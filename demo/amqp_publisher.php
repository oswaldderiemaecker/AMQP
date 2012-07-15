<?php

include(__DIR__ . '/config.php');
use AMQP\Connection\Connection;
use AMQP\Message\Message;

$exchange = 'router';
$queue = 'msgs';

$conn = new Connection(HOST, PORT, USER, PASS, VHOST);
$ch = $conn->channel();

/*
    The following code is the same both in the consumer and the producer.
    In this way we are sure we always have a queue to consume from and an
        exchange where to publish messages.
*/

/*
    name: $queue
    passive: false
    durable: true // the queue will survive server restarts
    exclusive: false // the queue can be accessed in other channels
    auto_delete: false //the queue won't be deleted once the channel is closed.
*/
$ch->queueDeclare($queue, false, true, false, false);

/*
    name: $exchange
    type: direct
    passive: false
    durable: true // the exchange will survive server restarts
    auto_delete: false //the exchange won't be deleted once the channel is closed.
*/

$ch->exchangeDeclare($exchange, 'direct', false, true, false);

$ch->queueBind($queue, $exchange);

$msg_body = implode(' ', array_slice($argv, 1));
$msg = new Message($msg_body, array('content_type' => 'text/plain', 'delivery_mode' => 2));
$ch->basicPublish($msg, $exchange);

$ch->close();
$conn->close();
?>
<?php

include __DIR__ . '/RabbitConfig.php';
include __DIR__ . "/vendor/autoload.php";

class Consumer {
    protected $exchange, $queue, $channel, $connection, $consumerTag;

    public function __construct($exchange, $queue, $consumerTag)
    {
        $this->exchange = $exchange;
        $this->queue    = $queue;
        $this->consumerTag  = $consumerTag;
        $this->connection   =
            new \PhpAmqpLib\Connection\AMQPStreamConnection(RABBIT_HOST, RABBIT_PORT, RABBIT_USER, RABBIT_PASS, RABBIT_VHOST);
        $this->channel      = $this->connection->channel();
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function run()
    {
        $this->channel->queue_declare($this->queue, false, true, false, false);

        $this->channel->exchange_declare($this->exchange, \PhpAmqpLib\Exchange\AMQPExchangeType::DIRECT, false, true, false);

        $this->channel->queue_bind($this->queue, $this->exchange);

        $this->channel->basic_consume($this->queue, $this->consumerTag, false, false, false, false, function ($message) {
            echo "message body: ", $message->body, PHP_EOL;

            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

            if ($message->body == 'quit' or $message->body == 'exit') {
                $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
            }
        });

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }

    }

    public function __destruct()
    {
        $this->connection->close();
        $this->channel->close();
    }
}

$consumer   = new Consumer('router', 'test', 'consumer');
$consumer->run();
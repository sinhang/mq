<?php

include __DIR__ . '/RabbitConfig.php';
include __DIR__ . "/vendor/autoload.php";

class Producer {
    protected $exchange, $queue, $channel, $connection;

    public function __construct($exchange, $queue)
    {
        $this->exchange = $exchange;
        $this->queue    = $queue;
        $this->connection   =
            new \PhpAmqpLib\Connection\AMQPStreamConnection(RABBIT_HOST, RABBIT_PORT, RABBIT_USER, RABBIT_PASS, RABBIT_VHOST);
        $this->channel      = $this->connection->channel();
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function put($message)
    {
        $this->channel->queue_declare($this->queue, false, true, false, false);

//        $this->channel->basic_qos(1, 1, 1);

        $this->channel->exchange_declare($this->exchange, \PhpAmqpLib\Exchange\AMQPExchangeType::DIRECT, false, true, false);

        $this->channel->queue_bind($this->queue, $this->exchange);

        echo $message, PHP_EOL;

        $message    = new \PhpAmqpLib\Message\AMQPMessage($message, [
            'content_type'  => 'text/plain',
            'delivery_mode' => \PhpAmqpLib\Message\AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

//        $this->channel->tx_select();

//        $this->channel->
        
        $this->channel->basic_publish($message, $this->exchange);
    }

    public function __destruct()
    {
        $this->connection->close();
        $this->channel->close();
    }
}
$start  = microtime(true);
$consumer   = new Producer('router', 'test');
for ($i = 0; $i < 100000; $i++) {
    $consumer->put(rand());
}
$end    = microtime(true);
echo ($end - $start), PHP_EOL;
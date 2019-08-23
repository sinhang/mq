<?php

include __DIR__ . "/vendor/autoload.php";

class Queue {
    protected $handler, $emitter, $stomp, $subscribe;

    public function __construct($subscribe)
    {
        $this->subscribe    = $subscribe;

        $this->handler  = new \Stomp\Client("tcp://localhost:61613");
        $this->stomp    = new \Stomp\StatefulStomp($this->handler);
    }

    public function put()
    {
        $this->stomp->begin();
        $send = $this->stomp->send($this->subscribe, new \Stomp\Transport\Message(rand()));
        $this->stomp->commit();
        echo $send, PHP_EOL;
    }
}

$queue   = new Queue('test');

for ($i = 0; $i < 100; $i++) {
    $queue->put();
}
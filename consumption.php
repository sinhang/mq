<?php

include __DIR__ . "/vendor/autoload.php";

class Consumption {

    protected $subscribe, $handler, $stomp;

    public function __construct($subscribe)
    {
        $this->subscribe    = $subscribe;

        $this->handler      = new \Stomp\Client("tcp://localhost:61613");

        $this->stomp        = new \Stomp\StatefulStomp($this->handler);

        $this->stomp->subscribe($this->subscribe, null, 'client-individual');

    }

    public function run()
    {
        while (true) {
            $msg    = $this->stomp->read();
            if ($msg) {
                $this->stomp->begin();
                echo $msg->getMessageId(), PHP_EOL;
                $this->stomp->ack($msg);
                $this->stomp->commit();
            }
        }
    }
}

$consumption = new Consumption('test');
$consumption->run();
<?php

require_once('./library/websocket/websockets.php');
require_once('./class/controller/instance.php');

class Server extends WebSocketServer {

    private $instance;

    public function __construct($addr, $port, $bufferLength = 2048) {
        parent::__construct($addr, $port, $bufferLength);
        $this->instance = new Instance();
    }

    protected function process($user, $message) {
        $data = json_decode($message);
        switch ($data[0]) {
            //Client login
            case 0:
                $player = $this->instance->login($user, $data[1]);
                if ($player != NULL) {
                    $this->sendAll(json_encode(array(0, $player->getId())));
                    $this->send($user, json_encode(array(3, $this->instance->getRound())));
                    $this->send($user, json_encode(array(4, $player->getState())));
                } else {
                    $this->send($user, json_encode(array(2)));
                }
                break;
            //Deployment
            case 1:
                $player = $this->instance->deployment($user, $data[1]);
                if ($player != NULL) {
                    $this->send($user, json_encode(array(4, $player->getState())));
                }
                break;
        }
    }

    protected function connected($user) {
        
    }

    protected function closed($user) {
        $player = $this->instance->logout($user);
        if ($player != NULL) {
            $this->sendAll(json_encode(array(1, $player->getId())));
        }
    }

}

$server = new Server("0.0.0.0", "666");

try {
    $server->run();
} catch (Exception $e) {
    $server->stdout($e->getMessage());
}

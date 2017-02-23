<?php

require_once('./library/websocket/websockets.php');

class Server extends WebSocketServer {

    protected function process($user, $message) {
        $this->send($user, $message);
    }

    protected function connected($user) {
        
    }

    protected function closed($user) {
        
    }

}

$server = new Server("0.0.0.0", "666");

try {
    $server->run();
} catch (Exception $e) {
    $server->stdout($e->getMessage());
}

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
                $this->stdout("Client login");
                $player = $this->instance->login($user, $data[1]);
                if ($player != NULL) {
                    $this->send($user, json_encode(array(3, $this->instance->getPlayers())));
                    $this->send($user, json_encode(array(4, $player->getState())));
                    $this->send($user, json_encode(array(5, $player->getTroop())));
                    $this->send($user, json_encode(array(8, $player->getObjectives())));
                    $this->send($user, json_encode(array(6, $this->instance->getViewTerritorysByPlayer($player))));
                } else {
                    $this->send($user, json_encode(array(2)));
                }
                break;
            //nextState
            case 1:
                $this->stdout("Client next state");
                $player = $this->instance->getPlayerByUser($user);
                if ($this->instance->state($player)) {
                    $this->send($user, json_encode(array(4, $player->getState())));
                    if ($player->getState() == 5) {
                        $this->send($this->instance->getPlayer()->getUser(), json_encode(array(4, $this->instance->getPlayer()->getState())));
                        if ($this->instance->checkRound()) {
                            foreach ($this->users as $u) {
                                $p = $this->instance->getPlayerByUser($u);
                                $this->send($u, json_encode(array(5, $p->getTroop())));
                            }
                        }
                    }
                }
                break;
            //Deployment
            case 2:
                $this->stdout("Deployment");
                $player = $this->instance->getPlayerByUser($user);
                if ($this->instance->deployment($player, $data[1][0], $data[1][1])) {
                    $this->send($user, json_encode(array(5, $player->getTroop())));
                    foreach ($this->users as $u) {
                        $p = $this->instance->getPlayerByUser($u);
                        $this->send($u, json_encode(array(6, $this->instance->getViewTerritorysByPlayer($p))));
                    }
                }
                break;
            //Message
            case 3:
                $this->stdout("Client message");
                $player = $this->instance->getPlayerByUser($user);
                foreach ($this->users as $u) {
                    $this->send($u, json_encode(array(7, array($player->getColor(), $data[1]))));
                }
                break;

            //Movement troop
            case 4:
                $this->stdout("Movement");
                $player = $this->instance->getPlayerByUser($user);
                if ($this->instance->movement($player, $data[1][0], $data[1][1], $data[1][2])) {
                    foreach ($this->users as $u) {
                        $p = $this->instance->getPlayerByUser($u);
                        $this->send($u, json_encode(array(6, $this->instance->getViewTerritorysByPlayer($p))));
                    }
                }
                break;
            case 5:
                $this->stdout("Attack");
                $player = $this->instance->getPlayerByUser($user);

                break;
            /*
              //Deployment
              case 1:
              $player = $this->instance->deployment($user, $data[1]);
              if ($player != NULL) {
              $this->send($user, json_encode(array(4, $player->getState())));
              }
              break;
              //Move
              case 2:
              $player = $this->instance->move($user);
              if ($player != NULL) {
              $this->send($user, json_encode(array(4, $player->getState())));
              }
              break;
              //Attack
              case 3:
              $player = $this->instance->attack($user);
              if ($player != NULL) {
              $this->send($user, json_encode(array(4, $player->getState())));
              }
              break;
              //Game
              case 4:
              $player = $this->instance->game($user);
              if ($player != NULL) {
              $this->send($user, json_encode(array(4, $player->getState())));
              }
              break;
              //Score
              case 5:
              $player = $this->instance->score($user);
              if ($player != NULL) {
              $this->send($user, json_encode(array(4, $player->getState())));
              $this->send($this->instance->getPlayer()->getUser(), json_encode(array(4, $this->instance->getPlayer()->getState())));
              }
              break; */
        }
    }

    protected function connected($user) {
        
    }

    protected function closed($user) {
        $player = $this->instance->getPlayerByUser($user);
        $this->instance->logout($player);
    }

}

$server = new Server("0.0.0.0", "666");

try {
    $server->run();
} catch (Exception $e) {
    $server->stdout($e->getMessage());
}

<?php

require_once('../serveur/library/websocket/websockets.php');
require_once('../serveur/class/controller/instance.php');
require_once('../serveur/class/controller/sql.php');

class Server extends WebSocketServer {

    private $instance;

    public function __construct($addr, $port, $bufferLength = 2048) {
        parent::__construct($addr, $port, $bufferLength);
        $this->instance = new Instance();
    }

    protected function process($user, $message) {
        $data = json_decode($message);
        $sql = new sql();
        switch ($data[0]) {
            //Client login
            case 0:
                $this->stdout("Client login");
                $player = $this->instance->login($user, $data[1]);
                if ($player != NULL) {
                    $pseudo = $sql->query("select * from user where idUser = " . $data[1])[0]["pseudo"];
                    $player->setPseudo($pseudo);
                    $this->send($user, json_encode(array(4, $player->getState())));
                    $this->send($user, json_encode(array(5, $player->getTroop())));
                    $this->send($user, json_encode(array(8, $player->getObjectives())));
                    $this->send($user, json_encode(array(6, $this->instance->getViewTerritorysByPlayer($player))));
                    foreach ($this->users as $u) {
                        $this->send($user, json_encode(array(3, $this->instance->getPlayers())));
                    }
                } else {
                    $this->send($user, json_encode(array(2)));
                }
                break;
            //nextState
            case 1:
                $this->stdout("Client next state");
                $player = $this->instance->getPlayerByUser($user);
                if ($this->instance->state($player)) {
                    if ($player->getState() == 3) {
                        foreach ($this->instance->getPlayerAttack() as $p) {
                            $this->send($p->getUser(), json_encode(array(4, $player->getState())));
                        }
                    } else {
                        $this->send($user, json_encode(array(4, $player->getState())));
                    }
                    if ($player->getState() == 5) {
                        $this->send($this->instance->getPlayer()->getUser(), json_encode(array(4, $this->instance->getPlayer()->getState())));
                        if ($this->instance->checkRound()) {
                            foreach ($this->users as $u) {
                                $p = $this->instance->getPlayerByUser($u);
                                $this->send($u, json_encode(array(5, $p->getTroop())));
                            }
                        }
                    }
                    if ($this->instance->finish) {
                        foreach ($this->users as $u) {
                            $this->send($u, json_encode(array(10)));
                        }
                        exit();
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
                        $this->send($u, json_encode(array(11, "Le joueur " . $player->getPseudo() . " déploie " . $data[1][1] . " troupes.")));
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
                        $this->send($u, json_encode(array(11, "Le joueur " . $player->getPseudo() . " déplace " . $data[1][2] . " troupes.")));
                    }
                }
                break;
            // Attack
            case 5:
                $this->stdout("Attack");
                $player = $this->instance->getPlayerByUser($user);
                if ($this->instance->attack($player, $data[1][0], $data[1][1], $data[1][2])) {
                    foreach ($this->users as $u) {
                        $p = $this->instance->getPlayerByUser($u);
                        $this->send($u, json_encode(array(6, $this->instance->getViewTerritorysByPlayer($p))));
                        $this->send($u, json_encode(array(11, "Le joueur " . $player->getPseudo() . " attaque un territoire avec " . $data[1][2] . " troupes.")));
                    }
                }
                break;
            // Mini-Game
            case 6:
                $this->stdout("Mini Game submit Time");
                $player = $this->instance->getPlayerByUser($user);
                if ($this->instance->time($player, $data[1])) {
                    $this->instance->state($this->instance->getPlayer());
                    $this->instance->resolution();
                    $score = $this->instance->getScore();
                    foreach ($this->users as $u) {
                        $this->send($u, json_encode(array(4, $this->instance->getPlayer()->getState())));
                        $this->send($u, json_encode(array(9, $score)));
                        $p = $this->instance->getPlayerByUser($u);
                        $this->send($u, json_encode(array(6, $this->instance->getViewTerritorysByPlayer($p))));
                        $this->send($u, json_encode(array(4, 5)));
                    }
                    $this->instance->freeAttack();
                    $this->instance->freeTime();
                }
                break;
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

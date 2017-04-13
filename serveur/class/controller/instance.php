<?php

require_once('../serveur/class/entity/player.php');
require_once('../serveur/class/entity/objectives/defeat.php');
require_once('../serveur/class/entity/objectives/number.php');

class Instance {

    private $config;
    private $player;
    private $step;
    private $players = array();
    private $territorys = array();
    private $attacks = array();
    public $finish = FALSE;

    //private $versus = array(array());
    //private $resultTimeGame = array();
    //private $resultGame = array();

    public function __construct() {
        $colors = array("#ffff00", "#ff0000", "#00ff00", "#0000cc");
        $this->step = 0;
        $this->config = require_once('../serveur/config.php');
        $this->territorys = require_once('../serveur/class/map/default.php');
        for ($i = 0; $i < $this->config->server_info["nbPlayer"]; $i++) {
            array_push($this->players, new Player("Player " . $i, $colors[$i]));
            for ($j = 0; $j < 4; $j++) {
                $this->territorys[$i * 10 + $j]->setPlayer($this->players[$i]);
            }
        }
        $n = rand(1, $this->config->server_info["nbPlayer"] - 1);
        for ($i = 0; $i < $this->config->server_info["nbPlayer"]; $i++) {
            $id = (($i + $n) % $this->config->server_info["nbPlayer"]);
            $this->players[$i]->addObjective(new Defeat($this->players[$id]));
            $this->players[$i]->addObjective(new Number($this->players[$i], $this));
        }
        $this->player = $this->players[0];
        $this->player->setState(0);
    }

    public function deployment($player, $idTerritory, $troop) {
        if ($this->player->getId() == $player->getId()) {
            if ($this->territorys[$idTerritory]->checkPlayer($player)) {
                if ($player->deployment($troop)) {
                    $this->territorys[$idTerritory]->addTroop($troop);
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function movement($player, $id1, $id2, $troop) {
        if ($this->player->getId() == $player->getId()) {
            if ($this->territorys[$id1]->checkPlayer($player)) {
                if ($this->territorys[$id2]->checkPlayer($player)) {
                    if ($this->territorys[$id1]->checkAdjacency($id2)) {
                        if ($this->territorys[$id1]->removeTroop($troop)) {
                            $this->territorys[$id2]->addTroop($troop);
                            return TRUE;
                        }
                    }
                }
            }
        }
        return FALSE;
    }

    public function attack($player, $id1, $id2, $troop) {
        if ($this->player->getId() == $player->getId()) {
            if ($this->territorys[$id1]->checkPlayer($player)) {
                if ($this->territorys[$id1]->checkAdjacency($id2)) {
                    if ($this->territorys[$id1]->removeTroop($troop)) {
                        $this->addAttack($id1, $id2, $troop);
                    }
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function resolution() {
        foreach ($this->attacks as &$attack) {
            $id1 = $attack[0];
            $id2 = $attack[1];
            $troop1 = $attack[2];
            $player = $this->territorys[$id1]->getPlayer();

            if (!$this->territorys[$id2]->checkPlayer($player)) {
                $troop2 = $this->territorys[$id2]->getTroop();

                // TODO: Comparer les temps et attribuer le bonus en conséquence

                if ($troop1 == $troop2) {
                    $this->territorys[$id2]->setTroop(1);
                    $attack[3] = $id2;
                } else if ($troop1 > $troop2) {
                    $this->territorys[$id2]->setTroop($troop1 - $troop2);
                    $this->territorys[$id2]->setPlayer($player);
                    $attack[3] = $id1;
                } else if ($troop1 < $troop2) {
                    $this->territorys[$id2]->setTroop($troop2 - $troop1);
                    $attack[3] = $id2;
                }
            } else {
                $this->movement($player, $id1, $id2, $troop1);
            }
        }
    }

    public function freeAttack() {
        $this->attacks = [];
    }

    public function state($player) {
        $state = $player->getState();
        if ($this->player->getId() == $player->getId()) {
            switch ($state) {
                case 0:
                    if ($player->getTroop() == 0) {
                        $state = 1;
                    }
                    break;
                case 1:
                    $state = 2;
                    break;
                case 2:
                    if (count($this->attacks) == 0) {
                        $state = 5;
                        $this->round();
                    } else {
                        $state = 3;
                    }
                    break;
                case 3:
                    $state = 4;
                    break;
                case 4:
                    $state = 5;
                    $this->round();
                    break;
            }
        }
        $result = $state != $player->getState();
        $player->setState($state);
        return $result;
    }

    //New Round
    public function round() {
        $this->step++;
        if ($this->checkRound()) {
            if ($this->player->checkObjectives()) {
                $this->finish = TRUE;
            }

            foreach ($this->players as $p) {
                $nbTerritory = count($this->getTerritorysByPlayer($p));
                $nbSysSolaire = 0;
                $p->assignTroop($nbTerritory, $nbSysSolaire);
                // TODO: Calculer le nombre de système solaire
            }
        }

        if (!$this->finish) {
            $idPlayer = -1;
            $nbTerritory = -1;
            $init = FALSE;

            do {
                if ($init) {
                    $this->step++;
                } else {
                    $init = TRUE;
                }
                $idPlayer = $this->step % $this->config->server_info["nbPlayer"];
                $this->player = $this->players[$idPlayer];
                $nbTerritory = count($this->getTerritorysByPlayer($this->players[$idPlayer]));
                if ($nbTerritory == 0) {
                    $this->player->lose();
                }
            } while ($this->player->isLose());

            $this->player->setState(0);
        }
    }

    public function login($user, $id) {
        foreach ($this->players as $player) {
            if ($player->checkId($id)) {
                $player->login($id, $user);
                return $player;
            }
        }
        return NULL;
    }

    public function logout($player) {
        if ($player != NULL) {
            $player->logout();
        }
    }

    public function checkRound() {
        return $this->step % $this->config->server_info["nbPlayer"] == 0;
    }

    private function addAttack($id1, $id2, $troop) {
        $new = true;
        foreach ($this->attacks as $attack) {
            if (($id1 == $attack[0]) && ($id2 == $attack[1])) {
                $attack[2] += $troop;
                $new = FALSE;
                break;
            }
        }
        if ($new) {
            array_push($this->attacks, array($id1, $id2, $troop, -1));
        }
    }

    public function time($player, $time) {
        $player->setTime($time);
        foreach ($this->getPlayerAttack() as $p) {
            if ($p->getTime() == -1) {
                return FALSE;
            }
        }
        return TRUE;
    }

    public function freeTime() {
        foreach ($this->players as $player) {
            $player->setTime(-1);
        }
    }

    public function getPlayerAttack() {
        $array = [];
        foreach ($this->attacks as $attack) {
            if (!isset($array[$attack[0]])) {
                if ($this->territorys[$attack[0]]->getPlayer() != NULL) {
                    $array[$attack[0]] = $this->territorys[$attack[0]]->getPlayer();
                }
            }
            if (!isset($array[$attack[1]])) {
                if ($this->territorys[$attack[1]]->getPlayer() != NULL) {
                    $array[$attack[1]] = $this->territorys[$attack[1]]->getPlayer();
                }
            }
        }
        return $array;
    }

    public function getPlayerByUser($user) {
        foreach ($this->players as $player) {
            if ($player->checkUser($user)) {
                return $player;
            }
        }
        return NULL;
    }

    public function getScore() {
        $array = [];
        $i = 0;
        foreach ($this->attacks as $attack) {
            $i++;
            if (!is_null($this->territorys[$attack[3]]->getPlayer())) {
                array_push($array, [$i, $this->territorys[$attack[3]]->getPlayer()->getPseudo()]);
            } else {
                array_push($array, [$i, "Bot"]);
            }
        }
        return $array;
    }

    public function getViewTerritorysByPlayer($player) {
        $array = [];
        foreach ($this->territorys as $territory) {
            $color = NULL;
            if ($territory->getPlayer() != null) {
                $color = $territory->getPlayer()->getColor();
            }
            array_push($array, array($territory->getId(), $territory->checkPlayer($player), $territory->getTroop(), $color));
        }
        return $array;
    }

    public function getTerritorysByPlayer($player) {
        $array = [];
        foreach ($this->territorys as $territory) {
            if ($territory->checkPlayer($player)) {
                array_push($array, $territory);
            }
        }
        return $array;
    }

    public function getPlayers() {
        $array = [];
        foreach ($this->players as $player) {
            array_push($array, array($player->getColor(), $player->getPseudo()));
        }
        return $array;
    }

    public function getPlayer() {
        return $this->player;
    }

    public function __toString() {
        $string = "Players: ";
        foreach ($this->players as $player) {
            $string .= $player;
        }
        return $string;
    }

}

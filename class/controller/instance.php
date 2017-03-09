<?php

require_once('./class/entity/player.php');

class Instance {

    private $config;
    private $player;
    private $step;
    private $players = array();
    private $territorys = array();
    private $attacks = array();

    public function __construct() {
        $this->step = 0;
        $this->config = require_once('./config.php');
        $this->territorys = require_once('./class/map/default.php');
        for ($i = 0; $i < $this->config->server_info["nbPlayer"]; $i++) {
            array_push($this->players, new Player("Player " . $i));
            $this->territorys[$i]->setPlayer($this->players[$i%2]);
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
        foreach ($this->attacks as $attack) {
            $id1 = $attack[0];
            $id2 = $attack[1];
            $troop1 = $attack[2];
            $player = $this->territorys[$id1]->getPlayer();

            if (!$this->territorys[$id2]->checkPlayer($player)) {
                $troop2 = $this->territorys[$id2]->getTroop();
                $this->territorys[$id1]->removeTroop($troop1);

                if ($troop1 == $troop2) {
                    $this->territorys[$id2]->setTroop(1);
                } else if ($troop1 > $troop2) {
                    $this->territorys[$id2]->setTroop($troop1 - $troop2);
                    $this->territorys[$id2]->setPlayer($player);
                } else if ($troop1 < $troop2) {
                    $this->territorys[$id2]->setTroop($troop2 - $troop1);
                }
            } else {
                $this->movement($player, $id1, $id2, $troop1);
            }
        }
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
        $this->nextPlayer();
        if ($this->checkRound()) {
            foreach ($this->players as $p) {
                $nbTerritory = 0;
                $nbSysSolaire = 0;

                foreach ($this->territorys as $ter) {
                    if ($ter->getPlayer() == $p) {
                        $nbTerritory++;
                    }
                }
                // TODO: Calculer le nombre de système solaire

                $p->setTroop($nbTerritory, $nbSysSolaire);
            }
        }
    }

    public function nextPlayer() {
        $this->step++;
        $idPlayer = $this->step % $this->config->server_info["nbPlayer"];
        $this->player = $this->players[$idPlayer];
        $this->player->setState(0);
    }

    public function login($user, $id) {
        foreach ($this->players as $player) {
            if ($player->checkId($id)) {
                if (!$player->isConnected()) {
                    $player->login($id, $user);
                    return $player;
                } else {
                    return $player;
                }
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

    public function addAttack($id1, $id2, $troop) {
        array_push($this->attacks, array($id1, $id2, $troop));
    }

    public function getPlayerByUser($user) {
        foreach ($this->players as $player) {
            if ($player->checkUser($user)) {
                return $player;
            }
        }
        return NULL;
    }

    public function getViewTerritorysByPlayer($player) {
        $array = [];
        foreach ($this->territorys as $territory) {
            array_push($array, array($territory->getId(), $territory->checkPlayer($player), $territory->getTroop()));
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

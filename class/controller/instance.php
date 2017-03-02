<?php

require_once('./class/entity/player.php');

class Instance {

    private $config;
    private $players = array();
    private $player;
    private $step;
    private $territorys = array();

    public function __construct() {
        $this->step = 0;
        $this->config = require_once('./config.php');
        $this->territorys = require_once('./class/map/default.php');
        for ($i = 0; $i < $this->config->server_info["nbPlayer"]; $i++) {
            array_push($this->players, new Player("Player " . $i));
            $this->territorys[$i]->setPlayer($this->players[$i]);
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
                    if ($this->territorys[$id1]->removeTroop($troop)) {
                        $this->territorys[$id2]->addTroop($troop);
                        return TRUE;
                    }
                }
            }
        }
        return FALSE;
    }

    public function attack($player, $id1, $id2, $troop) {
        if ($this->player->getId() == $player->getId()) {
            
        }
        return FALSE;
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
                    $state = 3;
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

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

    public function state($player) {
        $state = $player->getState();
        if ($this->player->getId() == $player->getId()) {
            switch ($state) {
                case 0:
                    $state = 1;
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

    public function round() {
        if ($this->step % $this->config->server_info["nbPlayer"] == 0) {
            //New Round
        }
        $this->nextPlayer();
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

    public function getPlayerByUser($user) {
        foreach ($this->players as $player) {
            if ($player->checkUser($user)) {
                return $player;
            }
        }
        return NULL;
    }

    public function getTerritorysByPlayer($player) {
        $array = [];
        foreach ($this->territorys as $territory) {
            if($player->checkId($territory->getPlayer()->getId())){
                 array_push($array, $territory->getId());
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

<?php

require_once('./class/entity/player.php');

class Instance {

    private $config;
    private $players = array();
    private $player;
    private $step;

    public function __construct() {
        $this->step = 0;
        $this->config = require_once('./config.php');
        for ($i = 0; $i < $this->config->server_info["nbPlayer"]; $i++) {
            array_push($this->players, new Player());
        }
        $this->player = $this->players[0];
        $this->player->setState(0);
    }

    public function nextPlayer() {
        $this->step++;
        $idPlayer = $this->step % $this->config->server_info["nbPlayer"];
        $this->player = $this->players[$idPlayer];
    }

    public function deployment($user, $troop) {
        $player = $this->getPlayerByUser($user);
        if ($this->player->getId() == $player->getId() && $this->player->getState() == 0) {
            if ($player->deployment($troop)) {
                $player->setState(1);
            }
        }
        return $player;
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

    public function logout($user) {
        $player = $this->getPlayerByUser($user);
        if ($player != NULL) {
            $player->logout();
        }
        return $player;
    }
    
    public function getRound(){
        return $this->step / $this->config->server_info["nbPlayer"];
    }

    public function getPlayerByUser($user) {
        foreach ($this->players as $player) {
            if ($player->checkUser($user)) {
                return $player;
            }
        }
        return NULL;
    }

    public function __toString() {
        $string = "Players: ";
        foreach ($this->players as $player) {
            $string .= $player;
        }
        return $string;
    }

}

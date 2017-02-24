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
            array_push($this->players, new Player("Player " . $i));
        }
        $this->player = $this->players[0];
        $this->player->setState(0);
    }

    public function nextPlayer() {
        $this->step++;
        $idPlayer = $this->step % $this->config->server_info["nbPlayer"];
        $this->player = $this->players[$idPlayer];
        $this->player->setState(0);
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

    public function move($user) {
        $player = $this->getPlayerByUser($user);
        if ($this->player->getId() == $player->getId() && $this->player->getState() == 1) {
            $player->setState(2);
        }
        return $player;
    }

    public function attack($user) {
        $player = $this->getPlayerByUser($user);
        if ($this->player->getId() == $player->getId() && $this->player->getState() == 2) {
            $player->setState(3);
        }
        return $player;
    }

    public function game($user) {
        $player = $this->getPlayerByUser($user);
        if ($this->player->getId() == $player->getId() && $this->player->getState() == 3) {
            $player->setState(4);
        }
        return $player;
    }

    public function score($user) {
        $player = $this->getPlayerByUser($user);
        if ($this->player->getId() == $player->getId() && $this->player->getState() == 4) {
            $player->setState(5);
            $this->nextPlayer();
        }
        return $player;
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
                    $this->nextPlayer();
                    break;
            }
        }
        $result = $state != $player->getState();
        $player->setState($state);
        return $result;
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

    public function getRound() {
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

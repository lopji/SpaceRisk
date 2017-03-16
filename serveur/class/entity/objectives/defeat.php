<?php

require_once('../serveur/class/entity/objective.php');

class Defeat extends Objective {

    private $player;

    public function __construct($player) {
        $this->player = $player;
    }

    public function check() {
        if ($this->player->isLose()) {
            return TRUE;
        }
        return FALSE;
    }

    public function __toString() {
        return "Défaire le joueur " . $this->player->getPseudo();
    }

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


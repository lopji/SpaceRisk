<?php

require_once('../serveur/class/entity/objective.php');

class Number extends Objective {

    const MAX = 5;
    private $instance;
    private $player;

    public function __construct($player, $instance) {
        $this->instance = $instance;
        $this->player = $player;
    }

    public function check() {
        if(count($this->instance->getTerritorysByPlayer($this->player)) > 5){
            return TRUE;
        }
        return FALSE;
    }

    public function __toString() {
        return "Conquérir " . self::MAX . " planètes !";
    }

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


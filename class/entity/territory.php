<?php

class Territory {

    private $id;
    private $neighbours = array();
    private $player;
    private $troop;

    public function __construct($id, $neighbours) {
        $this->id = $id;
        $this->neighbours = $neighbours;
        $this->troop = 1;
    }

    public function checkAdjacency($id) {
        foreach ($this->neighbours as $value) {
            if ($value == $id) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getId() {
        return $this->id;
    }

    public function addTroop($troop) {
        $this->troop += $troop;
    }

    public function removeTroop($troop) {
        if ($this->troop - $troop > 1) {
            $this->troop -= $troop;
            return TRUE;
        }
        return FALSE;
    }

    public function checkPlayer($player) {
        return $this->player == $player;
    }

    public function getTroop() {
        return $this->troop;
    }

    public function getPlayer() {
        return $this->player;
    }

    public function setPlayer($player) {
        $this->player = $player;
    }

}

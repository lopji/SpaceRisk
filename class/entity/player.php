<?php

class Player {

    private $id;
    private $user;
    private $connected;
    private $state;
    private $troop;
    private $pseudo;
    private $color;

    public function __construct($pseudo) {
        $this->id = -1;
        $this->user = null;
        $this->connected = FALSE;
        $this->state = 5;
        $this->troop = 20;
        $this->pseudo = $pseudo;
        $this->color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }

    public function login($id, $user) {
        $this->id = $id;
        $this->user = $user;
        $this->connected = TRUE;
    }

    public function logout() {
        $this->user = null;
        $this->connected = FALSE;
    }

    public function deployment($troop) {
        if ($this->state == 0) {
            if ($troop == $this->troop) {
                $this->troop = 0;
                return TRUE;
            } else if ($troop < $this->troop) {
                $this->troop -= $troop;
                return TRUE;
            }
        }
        return FALSE;
    }

    public function checkUser($user) {
        return $this->user->id == $user->id;
    }

    public function checkId($id) {
        return $this->id == $id || $this->id == -1;
    }

    public function isConnected() {
        return $this->connected;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function getState() {
        return $this->state;
    }

    public function getId() {
        return $this->id;
    }

    public function getUser() {
        return $this->user;
    }

    public function getPseudo() {
        return $this->pseudo;
    }

    public function getColor() {
        return $this->color;
    }

    public function getTroop() {
        return $this->troop;
    }

    public function __toString() {
        $connected = ($this->connected) ? 'TRUE' : 'FALSE';
        return "Connected: " . $connected . ";";
    }

}

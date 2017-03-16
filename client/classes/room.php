<?php

namespace classes;

use library\sql;
use classes\player;

class room {

    private $players = [];

    public function __construct($id) {
        $sql = new sql();
        //$room = $sql->query("select * from room where idRoom = $id;");
        $players = $sql->query("select * from player where idRoom = $id");
        foreach ($players as $player) {
            array_push($this->players, new player($player["idUser"], $id));
        }
    }

    public function getPlayers() {
        $result = [];
        foreach ($this->players as $player) {
            array_push($result, $player->getPlayer());
        }
        return $result;
    }
    
    public function checkReady(){
        if(count($this->players) != 4){
            return FALSE;
        }
        foreach ($this->players as $player){
            if(!$player->isReady()){
                return FALSE;
            }
        }
        return TRUE;
    }

}

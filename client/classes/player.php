<?php

namespace classes;

use library\sql;

class player {

    private $pseudo;
    private $ready;

    public function __construct($idUser, $idRoom) {
        $sql = new sql();
        $player = $sql->query("select * from player join user on user.idUser = player.idUser where user.idUser = $idUser and idRoom = $idRoom;");
        $this->pseudo = $player[0]["pseudo"];
        if ($player[0]["ready"]) {
            $this->ready = "Oui";
        } else {
            $this->ready = "Non";
        }
    }

    public function getPlayer() {
        return ["pseudo" => $this->pseudo, "ready" => $this->ready];
    }
    
    public function isReady(){
        if($this->ready == "Oui"){
            return TRUE;
        }
        return FALSE;
    }

}

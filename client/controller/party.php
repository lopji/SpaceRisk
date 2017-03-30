<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controller;

use library\parser;
use library\sql;
use classes\room;

/**
 * Description of lobby
 *
 * @author rocha
 */
class party extends prefab {

    public function index() {
        $parser = new parser();
        $sql = new sql();

        $id = $_GET["id"];

        if (isset($_POST['ready'])) {
            $sql->query("UPDATE `player` SET `ready` = '1' WHERE `player`.`idUser` = '" . $this->user->getId() . "' AND `player`.`idRoom` = $id;");
        }

        $room = new room($id);
        $this->data["players"] = $room->getPlayers();

        if ($room->checkReady()) {
            header('Location: index.php?pages=game&id=' . $id);
            exit();
        }

        $file = $parser->logged(file_get_contents("./views/pages/party.html"), $this->user->isLogin());
        return $parser->parse($file, $this->data);
    }

}

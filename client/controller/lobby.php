<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controller;

use library\parser;
use library\sql;

/**
 * Description of lobby
 *
 * @author rocha
 */
class lobby extends prefab {

    public function index() {
        $parser = new parser();
        $sql = new sql();

        $player = $sql->query("SELECT * FROM player join room on room.idRoom = player.idRoom where idUser = '" . $this->user->getId() . "' order by room.idRoom desc");
        if (count($player)) {
            if ($player[0]["state"] == 0) {
                header('Location: index.php?pages=party&id=' . $player[0]["idRoom"]);
                exit;
            } else if ($player[0]["state"] == 1) {
                header('Location: index.php?pages=game&id=' . $player[0]["idRoom"]);
                exit;
            }
        }

        if (isset($_POST['create'])) {
            $id = 0;
            $name = $_POST['room'];
            $sql->query("INSERT INTO `room` (`idRoom`, `state`, `name`, `port`, `user_iduser`) VALUES (NULL, '0', '$name', NULL, '" . $this->user->getId() . "');", $id);
            $sql->query("INSERT INTO `player` (`idUser`, `idRoom`, `ready`) VALUES ('" . $this->user->getId() . "', '$id', '0');");
            header('Location: index.php?pages=party&id=' . $id);
            exit;
        }

        if (isset($_POST['join'])) {
            $id = $_POST['id'];
            $sql->query("INSERT INTO `player` (`idUser`, `idRoom`, `ready`) VALUES ('" . $this->user->getId() . "', '$id', '0');");
            header('Location: index.php?pages=party&id=' . $id);
            exit;
        }

        $this->data["rooms"] = $sql->query("SELECT * FROM `room` JOIN user on idUser = user_iduser  where `state` = 0;");
        $file = $parser->logged(file_get_contents("./views/pages/lobby.html"), $this->user->isLogin());

        return $parser->parse($file, $this->data);
    }

}

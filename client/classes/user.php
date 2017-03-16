<?php

namespace classes;

use library\sql;

class user {

    private $id = 0;
    private $name = "";
    private $login = FALSE;

    public function isLogin() {
        return $this->login;
    }

    public function connexion($name, $password) {
        if (!$this->isLogin()) {
            if ($this->checkAccount($name, $password)) {
                $this->name = $name;
                $this->login = TRUE;
                return TRUE;
            }
        }
        return FALSE;
    }
    
    public function logout(){
        $this->login = FALSE;
    }

    public function checkUser($name) {
        $sql = new sql();
        $data = $sql->query("select * from user where pseudo = '$name'");
        if (count($data) > 0) {
            return TRUE;
        }
        return FALSE;
    }

    public function checkAccount($name, $password) {
        $sql = new sql();
        $data = $sql->query("select * from user where pseudo = '$name'");
        if (count($data) > 0) {
            if ($password == $data['0']['password']) {
                $this->id = $data['0']['idUser'];
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getId(){
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }

}

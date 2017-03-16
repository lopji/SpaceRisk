<?php

namespace controller;

use library\parser;
use library\sql;

/**
 * inscription
 *
 * Inscription au site web
 *
 * @category  controller
 * @package   controller
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @example   ../index.php
 * @example <br />
 * $register = new register(); <br />
 * echo $register->index();
 * @version   1.0
 * @since     inconnue
 * @author    Julien Rochat
 */
class register extends prefab {

    public function index() {
        $parser = new parser();

        $this->data['error']['pseudo'] = "";
        $this->data['error']['password'] = "";
        
         if (isset($_POST['register'])) {
            $pseudo = $_POST['pseudo'];
            $password = $_POST['password'];
            if($this->pseudo($pseudo)){
                 $sql = new sql();
                 $sql->query("INSERT INTO `user` (`idUser`, `pseudo`, `password`) VALUES (NULL, '$pseudo', '$password');");
                 header('Location: index.php?pages=login');
                 exit;
            }
         }
     
        $file = $parser->logged(file_get_contents("./views/pages/register.html"), $this->user->isLogin());
        return $parser->parse($file, $this->data);
    }

    private function pseudo($pseudo){
        if (!empty($pseudo)) {    
           if($this->user->checkUser($pseudo)){
               $this->data['error']['pseudo'] = "Le pseudo existe déjà";
               return FALSE;
           }   
        }else{
            $this->data['error']['pseudo'] = "Merci de remplir ce champs";
            return FALSE;
        }
        return TRUE;
    }
}

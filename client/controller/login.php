<?php

namespace controller;

use library\parser;

/**
 * login
 *
 * La page de login
 *
 * @category  controller
 * @package   controller
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @example   ../index.php
 * @example <br />
 * $login = new login(); <br />
 * echo $login->index();
 * @version   1.0
 * @since     inconnue
 * @author    Julien Rochat
 */
class login extends prefab {

    public function index() {
        $parser = new parser();

        $pseudo = "";
        $error = "";

        if (isset($_POST['login'])) {
            $pseudo = $_POST['pseudo'];
            $password = $_POST['password'];
            if ($this->user->connexion($pseudo, $password)){
                header('location: index.php?pages=index');
                exit;
            }else{
                $error = "Pseudo ou mot de passe incorrecte";
            }
        }

        $this->data["pseudo"] = $pseudo;
        $this->data["error"] = $error;

        $file = $parser->logged(file_get_contents("./views/pages/login.html"), $this->user->isLogin());
        return $parser->parse($file, $this->data);
    }

}

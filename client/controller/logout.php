<?php

namespace controller;

/**
 * déconnexion
 *
 * La page de déconnexion
 *
 * @category  controller
 * @package   controller
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @example   ../index.php
 * @example <br />
 * $logout = new logout(); <br />
 * echo $logout->index();
 * @version   1.0
 * @since     inconnue
 * @author    Julien Rochat
 */
class logout extends prefab {

    public function index() {
        if ($this->user->isLogin()) {
            $this->user->logout();
        }
        header('location: index.php?pages=index');
        exit;
    }

}

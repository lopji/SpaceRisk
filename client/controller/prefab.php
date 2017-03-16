<?php

namespace controller;

use classes\user;

/**
 * prefab
 *
 * L'interface qui permet de gérer les différents controller
 *
 * @category  controller
 * @package   controller
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @example   ../index.php
 * @version   1.0
 * @since     inconnue
 * @author    Julien Rochat
 */
abstract class prefab {

    /**
     * Contient les différentes données de la page
     * @var data
     */
    protected $data = array();

    /**
     * Contient l'utilisateur
     * @var user
     */
    protected $user = null;

    /**
     * Permet de gérer l'utilisateur
     *
     *
     * @since     unknown
     * @author    Julien Rochat
     */
    public function __construct() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['user'] = new user();
        }
        $this->user = $_SESSION['user'];
    }

    /**
     * Permet de gérer le rendu de la page
     *
     *
     * @since     unknown
     * @author    Julien Rochat
     */
    abstract public function index();
}

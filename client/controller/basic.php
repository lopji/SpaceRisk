<?php

namespace controller;

use library\parser;

/**
 * basic
 *
 * La première page chargé
 *
 * @category  controller
 * @package   controller
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @example   ../index.php
 * @example <br />
 * $basic = new basic(); <br />
 * echo $basic->index();
 * @version   1.0
 * @since     inconnue
 * @author    Julien Rochat
 */
class basic extends prefab {

    public function index() {
        $this->controller();
        $parser = new parser();
        $pages = filter_input(INPUT_GET, "pages");
        if ($pages == "game") {
            $file = $parser->logged(file_get_contents("./views/game.html"), $this->user->isLogin());
        } else {
            $file = $parser->logged(file_get_contents("./views/default.html"), $this->user->isLogin());
        }
        return $parser->parse($file, $this->data);
    }

    /**
     * Permet de gérer les différentes pages du site web
     *
     *
     * @since     unknown
     * @author    Julien Rochat
     */
    private function controller() {
        if (isset($_GET["pages"])) {
            $controller = './controller/' . $_GET['pages'] . '.php';
            if (file_exists($controller)) {
                $class = 'controller\\' . $_GET['pages'] . '';
            } else {
                $class = 'controller\notFound';
            }
        } else {
            $class = 'controller\index';
        }

        $class = new $class();
        $this->data["pages"] = $class->index();
    }

}

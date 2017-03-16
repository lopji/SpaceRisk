<?php

namespace controller;

use library\parser;

/**
 * index
 *
 * La page chargé par défaut
 *
 * @category  controller
 * @package   controller
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @example   ../index.php
 * @example <br />
 * $index = new index(); <br />
 * echo $index->index();
 * @version   1.0
 * @since     inconnue
 * @author    Julien Rochat
 */
class index extends prefab {

    public function index() {
        $parser = new parser();
        $file = $parser->logged(file_get_contents("./views/pages/index.html"), $this->user->isLogin());
        return $parser->parse($file, $this->data);
    }

}

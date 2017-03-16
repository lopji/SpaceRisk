<?php

namespace controller;

use library\parser;

/**
 * erreur 404
 *
 * Page non trouvé
 *
 * @category  controller
 * @package   controller
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @example   ../index.php
 * @example <br />
 * $notFound = new notFound(); <br />
 * echo $notFound->index();
 * @version   1.0
 * @since     inconnue
 * @author    Julien Rochat
 */
class notFound extends prefab {

    public function index() {
        $parser = new parser();
        $file = $parser->logged(file_get_contents("./views/pages/notFound.html"), $this->user->isLogin());
        return $parser->parse($file, $this->data);
    }

}

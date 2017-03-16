<?php

namespace controller;

use library\parser;


class about extends prefab {

    public function index() {
        $parser = new parser();
        $file = $parser->logged(file_get_contents("./views/pages/about.html"), $this->user->isLogin());
        return $parser->parse($file, $this->data);
    }

}

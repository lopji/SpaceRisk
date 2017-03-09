<?php

require_once './class/entity/territory.php';

return array(
    new Territory(0, array(1)),
    new Territory(1, array(0, 2, 3)),
    new Territory(2, array(1, 3)),
    new Territory(3, array(1, 2)) 
);


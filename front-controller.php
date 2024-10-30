<?php

use StMartinWof\AltozRouter;

$router = new AltozRouter();


$router->map(
    'GET',
    '/stmartin-wof/home/',
    function() {
        echo 'hello world';
    },
    'woz-home'
);

$router->run();

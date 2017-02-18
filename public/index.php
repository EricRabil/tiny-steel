<?php

/*
 * After this require, all Steel classes, instances, objects are loaded. Steel has not been run yet.
 */
require '../classes/TinySteel/TinySteel.php';

$steel = new TinySteel\TinySteel;

$steel->map(new \TinySteel\MVC\MVCIdentifier('MVC-INDEX', 'index', 'IndexModel', 'IndexView', 'IndexController', [], []));

$steel->init();

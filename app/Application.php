<?php

namespace MyCoolApplicationNamespace;

class MyCoolApplication implements \TinySteel\IApplication {

    private $steel;
    private $bundle;
    private $args;
    private $intercepted_classes = ['index'];

    public function __construct(\TinySteel\TinySteel $steel) {
        $this->steel = $steel;
    }

}

<?php

class IndexModel implements \TinySteel\MVC\IModel {

    public $steel;
    public $context = [];

    public function __construct(\TinySteel\TinySteel $steel) {
        $this->steel = $steel;
    }

    public function get_context() {
        return $this->context;
    }

}

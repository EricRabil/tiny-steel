<?php

class IndexController implements \TinySteel\MVC\IController {

    private $model;
    private $bundle;

    public function __construct(\TinySteel\MVC\MVCBundle $bundle) {
        $this->bundle = $bundle;
        $this->model = $this->bundle->get_model();
    }

    public function main($params) {
        
    }

}

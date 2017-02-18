<?php

class IndexView implements \TinySteel\MVC\IView {

    private $bundle;
    private $model;

    public function __construct(\TinySteel\MVC\MVCBundle $bundle) {
        $this->bundle = $bundle;
        $this->model = $this->bundle->get_model();
    }

    public function render() {
        $this->model->steel->render($this->model);
    }

}

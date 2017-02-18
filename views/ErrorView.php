<?php

class ErrorView implements \TinySteel\MVC\IView {

    private $bundle;
    private $model;

    public function __construct(\TinySteel\MVC\MVCBundle $bundle) {
        $this->bundle = $bundle;
        $this->model = $this->bundle->get_model();
    }

    public function render() {
        $this->model->context['is_error'] = true;
        $this->model->context['error_title'] = $this->model->get_error_title();
        $this->model->context['error_text'] = $this->model->get_error_text();
        $this->model->steel->render($this->model);
    }

}

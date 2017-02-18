<?php

class ErrorController implements \TinySteel\MVC\IErrorController {

    private $model;
    private $bundle;
    private $error_map = [
        404 => 'not_found',
        2 => 'not_found',
        3 => 'internal_error'
    ];

    public function __construct(\TinySteel\MVC\MVCBundle $bundle) {
        $this->model = $bundle->get_model();
        $this->bundle = $bundle;
    }

    public function main($params) {
        return;
    }

    public function parse_error($code, $args) {
        if (array_key_exists($code, $this->error_map)) {
            $func = $this->error_map[$code];
            $this->$func($args);
        }
    }

    public function internal_error($args) {
        $this->model->set_error_text($args['message']);
        $this->model->set_error_title("Internal Server Error");
    }

    public function not_found($args) {
        $message = sprintf("Sorry! %s couldn't be found.", $args['path']);
        $this->model->set_error_text($message);
        $this->model->set_error_title("Not Found");
    }

}

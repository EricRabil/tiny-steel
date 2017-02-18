<?php

class ErrorModel implements \TinySteel\MVC\IErrorModel {

    private $errorText;
    private $errorTitle;
    private $errorType = "error";
    public $steel;
    public $context = [];

    public function __construct(\TinySteel\TinySteel $steel) {
        $this->errorText = "Something went wrong!";
        $this->errorTitle = "Uh-oh.";
        $this->steel = $steel;
    }

    public function get_error_text() {
        return $this->errorText;
    }

    public function get_error_title() {
        return $this->errorTitle;
    }

    public function get_error_type() {
        return $this->errorType;
    }

    public function set_error_text($text) {
        $this->errorText = $text;
    }

    public function set_error_title($title) {
        $this->errorTitle = $title;
    }

    public function set_error_type($type) {
        $this->errorType = $type;
    }

    public function get_context() {
        return $this->context;
    }

}

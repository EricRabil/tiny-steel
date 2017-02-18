<?php

namespace TinySteel\MVC;

/**
 * Special interface for the Error Model component of the Error MVC
 * 
 * @since   1.0
 * @author  Eric Rabil <ericjrabil@gmail.com>
 */
interface IErrorModel extends \TinySteel\MVC\IModel {

    /**
     * Updates the error title to be displayed, if any
     * 
     * @param string $title
     */
    public function set_error_title($title);

    /**
     * Gets the error title to be displayed, if any
     * 
     * @return string
     */
    public function get_error_title();

    /**
     * Sets the error message to be displayed
     * 
     * @param string $text
     */
    public function set_error_text($text);

    /**
     * Gets the error message to be displayed
     * 
     * @return string
     */
    public function get_error_text();

    /**
     * Set error type (no default purpose, to be used by applications or MVC)
     * 
     * @param string $type
     */
    public function set_error_type($type);

    /**
     * Get error type
     * 
     * @return string
     */
    public function get_error_type();

    /**
     * Get the context (variables that the template will have access to.
     * 
     * @return array
     */
    public function get_context();
}

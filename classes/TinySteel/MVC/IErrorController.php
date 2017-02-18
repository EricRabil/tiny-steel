<?php

namespace TinySteel\MVC;

/**
 * Special interface for the Error Controller component of the Error MVC
 * 
 * @since   1.0
 * @author  Eric Rabil <ericjrabil@gmail.com>
 */
interface IErrorController extends IController {

    /**
     * Error handler for 404 errors
     * 
     * @param type $url The URL the user tried accessed
     */
    public function not_found($url);

    /**
     * Error handler for internal errors
     * 
     * @param array $args An array containing error metadata
     */
    public function internal_error($args);
    
    /**
     * Parse the error code and display it.
     * 
     * @param int $code The error code to display
     * @param array $args The arguments, unique to the error code.
     */
    public function parse_error($code, $args);
}

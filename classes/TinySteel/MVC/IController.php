<?php

namespace TinySteel\MVC;

/**
 * Interface for the controller component (logic) of MVC
 * 
 * @since   1.0
 * @author  Eric Rabil <ericjrabil@gmail.com>
 */
interface IController {

    /**
     * Constructor for any controllers.
     * 
     * @param \TinySteel\MVC\MVCBundle $bundle Gives the controller the MVCBundle so it can assign its variables
     */
    public function __construct(\TinySteel\MVC\MVCBundle $bundle);

    /**
     * Runs the controller with optionally added parameters
     * 
     * @param array $params Any added instructions for the controller
     */
    public function main($params);
}

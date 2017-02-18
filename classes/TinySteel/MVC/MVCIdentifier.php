<?php

namespace TinySteel\MVC;

/**
 * Storage container for the Model, View and Controller of an MVC.
 * 
 * @since   1.0
 * @author  Eric Rabil <ericjrabil@gmail.com>
 */
class MVCIdentifier {

    /**
     * The unique ID of the MVC (Example: MVC-YOURCOOLMVC)
     * 
     * @var string
     */
    private $uid;

    /**
     * The top-level path of the MVC.
     * 
     * @var string
     */
    private $path;

    /**
     * The file/class name of the model.
     * 
     * @var string
     */
    private $modelName;

    /**
     * The file/class name of the view.
     * 
     * @var string
     */
    private $viewName;

    /**
     * The file/class name of the controller.
     * 
     * @var string
     */
    private $controllerName;

    /**
     * The list of paths (functions) that cannot be called by the user.
     * 
     * @var array
     */
    private $forbiddenPaths;

    /**
     * Provides all the data necessary to create the identifier.
     * 
     * @param string $uid The unique MVC identifier
     * @param string $path The path used to tell Steel when to call this MVC
     * @param string $model The name of the file/class of the model.
     * @param string $view The name of the file/class of the view.
     * @param string $controller The name of the file/class of the controller.
     * @param array $forbidden The array of forbidden functions.
     * @param array $dependencies The array of required files.
     */
    public function __construct($uid, $path, $model, $view, $controller, $forbidden = ['__construct', 'main']) {
        $this->uid = $uid;
        $this->path = $path;
        $this->modelName = $model;
        $this->viewName = $view;
        $this->controllerName = $controller;
        if (!in_array('__construct', $forbidden)) {
            array_push($forbidden, '__construct');
        }
        if (!in_array('main', $forbidden)) {
            array_push($forbidden, 'main');
        }
        $this->forbiddenPaths = $forbidden;
    }

    /**
     * Gets the assigned path for this MVC
     * 
     * @return string
     */
    public function get_path() {
        return $this->path;
    }

    /**
     * Gets the name of the file/class of the model
     * 
     * @return string
     */
    public function get_model_name() {
        return $this->modelName;
    }

    /**
     * Gets the name of the file/class of the view
     * 
     * @return string
     */
    public function get_view_name() {
        return $this->viewName;
    }

    /**
     * Gets the name of the file/class of the controller
     * 
     * @return string
     */
    public function get_controller_name() {
        return $this->controllerName;
    }

    /**
     * Gets the list of forbidden functions
     * 
     * @return array
     */
    public function get_forbidden_paths() {
        return $this->forbiddenPaths;
    }

    /**
     * Gets the unique ID of the MVC
     * 
     * @return string
     */
    public function get_uid() {
        return $this->uid;
    }
}

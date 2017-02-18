<?php

namespace TinySteel;

require_once 'autoload.php';

use Steel\Database\Connection;

/**
 * This is the main Steel class; it is used for code execution and data storage.
 * 
 * @since   1.0
 * @author  Eric Rabil <ericjrabil@gmail.com>
 */
class TinySteel {

    private $mvcMap = [];
    private $components;
    public $directories = [];
    private $path;
    public $config;
    private $initialized = false;
    private $dir;
    private $sreheader = "<!-- Steel Runtime Error: ";
    public $application;
    
    public function __construct($directories = ['models' => __DIR__ . '/../../models/', 'views' => __DIR__ . '/../../views/', 'controllers' => __DIR__ . '/../../controllers/']) {
        $this->directories = $this->sanitize_path_map($directories);
    }
    
    private function sanitize_path_map($array){
        $required_keys = ['models' => __DIR__ . '/../../models/', 'views' => __DIR__ . '/../../views/', 'controllers' => __DIR__ . '/../../controllers/'];
        $keys = array_keys($array);
        foreach(array_keys($required_keys) as $key){
            if(!in_array($key, $keys) || !file_exists($array[$key])){
                $this->throw_sre('The configured '.$key.' directory does not exist or is invalid. It has been ignored and the default was used instead.');
                $array[$key] = $required_keys[$key];
            }
        }
        return $array;
    }
    
    /**
     * Prints an HTML comment with the desired error
     * 
     * Should only be used when urgent. Do not use this for compromising information.
     * 
     * @param string $message The message to pass.
     */
    public function throw_sre($message){
        echo $this->sreheader . $message . ' -->'.PHP_EOL;
    }

    /**
     * Maps an MVC to Steel.
     * 
     * Takes the identifier and pushes it to an array using a key identical to the value of $identifier->get_path()
     *
     * @param MVCIdentifier $identifier The MVCIdentifier associated with the MVC
     */
    public function map(\TinySteel\MVC\MVCIdentifier $identifier) {
        $this->mvcMap[$identifier->get_path()] = $identifier;
    }

    /**
     * Runs the Steel Framework.
     * 
     * Upon run, it configures itself and loads the necessary MVC (if mapped)
     */
    public function init() {
        if (!$this->initialized) {
            $this->dir = dirname(__FILE__);
            $this->path = trim(preg_replace("/[^a-z0-9_\\/]+/i", "", (isset($_GET['method'])) ? $_GET['method'] : 'index'), '/');
            $conf = new \TinySteel\Settings();
            $conf->setup();
            $this->config = $conf->getConfig();
            $this->config['steel']['useSessions'] ? session_start() : null;
            $this->config['steel']['autoinclude'] ? $this->require_includes() : null;
            $this->config['steel']['useApplication'] ? $this->use_app_controller() : $this->application = null;
            $this->process_request();
            $this->initialized = true;
        }
    }

    private function use_app_controller() {
        if ($this->config['steel']['useApplication']) {
            require_once $this->config['steel']['application']['filepath'];
            $this->application = new $this->config['steel']['application']['fully_qualified_name']($this);
            $reflection = new \ReflectionClass($this->application);
            if (!$reflection->implementsInterface('\TinySteel\IApplication')) {
                echo get_class($this->application) . " must be implement \TinySteel\IApplication";
                exit();
            }
        } else {
            return false;
        }
    }

    /**
     * Get the configuration class
     * 
     * @return Settings
     */
    public function get_config() {
        return $this->config;
    }

    private function require_include_folder() {
        $files = scandir($this->dir . "/../../include");
        $include = [];
        foreach ($files as $file) {
            $extension = explode('.', $file);
            (isset($extension[1]) && !empty($extension[1]) && $extension[1] === "php") ? array_push($include, $file) : null;
        }
        foreach ($include as $file) {
            require $this->dir . "/../../include/" . $file;
        }
    }

    private function require_includes() {
        if (!file_exists($this->dir . '/../../include')) {
            if (!is_writable($this->dir . '/../..')) {
                $this->throw_sre('Failed to create missing \'include\' directory. Check that PHP has the proper execution permissions.');
            } else {
                mkdir($this->dir . '/../../include', 0755, true);
                $this->require_include_folder();
            }
        } else {
            $this->require_include_folder();
        }
    }

    /**
     * Get the mapped MVCIdentifiers
     * 
     * @return array The array with the mapped MVCIdentifier
     */
    public function get_mvc_map() {
        return $this->mvcMap;
    }

    private function process_request() {
        $this->components = explode('/', $this->path);
        $class = $this->components[0];
        if (!array_key_exists($class, $this->mvcMap)) {
            $this->display_error(2, ['path' => $this->path]);
            return;
        }
        $mvcID = $this->mvcMap[$class];
        $mvc = new \TinySteel\MVC\MVCBundle($this, $mvcID);
        $status = $mvc->runMVC();
        if ($status != 1) {
            switch ($status) {
                case 2:
                    $this->display_error(2, ['path' => $this->path]);
                    break;
                case 3:
                    $this->display_error(3, ['message' => "MVC " + $mvcID->get_uid + " has already been executed."]);
                    break;
            }
        }
    }

    /**
     * Get the components of the URL path
     * 
     * @return array Array of the path components
     */
    public function get_components() {
        return $this->components;
    }

    /**
     * Run the Error MVC with the specified error
     * 
     * @param integer $int Error code
     * @param array $args Array with the error arguments (unique to each error code)
     */
    public function display_error($int, $args) {
        $errorID = new \TinySteel\MVC\MVCIdentifier('MVC-ERR', 'error', 'ErrorModel', 'ErrorView', 'ErrorController', ['__construct', 'main'], [$this->dir . '/MVC/IErrorModel.php', $this->dir . '/MVC/IErrorController.php']);
        $mvc = new \TinySteel\MVC\MVCBundle($this, $errorID);
        $mvc->init();
        $reflection = new \ReflectionClass($mvc->get_controller());
        if($reflection->implementsInterface('\TinySteel\MVC\IErrorController')){ 
            $mvc->get_controller()->parse_error($int, $args);
            $mvc->get_view()->render();
        }else{
            trigger_error("ErrorController must implement \TinySteel\MVC\IErrorController", E_USER_ERROR);
        }
    }

    /**
     * Get the full path, not the components.
     * 
     * @return string
     */
    public function get_path() {
        return $this->path;
    }

    /**
     * Run the desired MVC
     * 
     * @param \TinySteel\MVC\IModel $model Data source for page context
     * @param type $page Template file to load
     * @param type $styles CSS files to load
     * @param type $scripts JS files to load
     * @param \TinySteel\MVC\RenderConfiguration $configuration The configuration array to load extra settings from.
     */
    
    public function render(\TinySteel\MVC\IModel $model) {
        $context = $model->get_context();
        if(!isset($context['is_error'])){
            $context['is_error'] = false;
        }
        extract($context);
        require_once $this->dir . '/../../templates/index.phtml';
    }

}  

<?php

namespace TinySteel;

require_once 'autoload.php';

/**
 * This is the main Steel class; it is used for code execution and data storage.
 * 
 * @since   1.0
 * @author  Eric Rabil <ericjrabil@gmail.com>
 */
class TinySteel {

    private $components;
    public $directories = [];
    private $path;
    public $config;
    private $initialized = false;
    private $dir;
    
    public function __construct($directories = ['models' => __DIR__ . '/../../models/', 'views' => __DIR__ . '/../../views/', 'controllers' => __DIR__ . '/../../controllers/']) {
        $this->directories = $this->sanitize_path_map($directories);
    }
    
    private function sanitize_path_map($array){
        $required_keys = ['models' => __DIR__ . '/../../models/', 'views' => __DIR__ . '/../../views/', 'controllers' => __DIR__ . '/../../controllers/'];
        $keys = array_keys($array);
        foreach(array_keys($required_keys) as $key){
            if(!in_array($key, $keys) || !file_exists($array[$key])){
                $array[$key] = $required_keys[$key];
            }
        }
        return $array;
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
            $this->process_request();
            $this->initialized = true;
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
            if (is_writable($this->dir . '/../..')) {
                mkdir($this->dir . '/../../include', 0755, true);
                $this->require_include_folder();
            }
        } else {
            $this->require_include_folder();
        }
    }

    private function process_request() {
        $mvc = new \TinySteel\MVC\MVCBundle($this, new \TinySteel\MVC\MVCIdentifier('MVC-INDEX', 'index', 'IndexModel', 'IndexView', 'IndexController', [], []));
        $status = $mvc->runMVC();
        if ($status != 1) {
            switch ($status) {
                case 2:
                    $this->display_error(2, ['path' => $this->path]);
                    break;
                case 3:
                    $this->display_error(3, ['message' => "MVC " + $mvc->get_mvc_identifier()->get_uid() + " has already been executed."]);
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
        $mvc = new \TinySteel\MVC\MVCBundle($this, new \TinySteel\MVC\MVCIdentifier('MVC-ERR', 'error', 'ErrorModel', 'ErrorView', 'ErrorController', ['__construct', 'main'], [$this->dir . '/MVC/IErrorModel.php', $this->dir . '/MVC/IErrorController.php']));
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

<?php

/*
 * Settings API
 *
 * Settings uses multi-dimensional arrays; this is for settings structure and organization.
 * An example of this is as follows
 *
 * Category: General
 * Setting: Host
 * Variable: $this->config['general']['host']
 *
 * Category: General
 * Sub-Category: JS Links
 * Setting: Local
 * Variable: $this->config['general']['js_links']['local']
 *
 * Naming conventions are all lower case, alpha-numeric, and underscores for spaces.
 *
 * Testing Testing 123 would become testing_testing_123
 */

namespace TinySteel;

class Settings {

    private $config = [];

    public function setup() {
        $this->config['steel'] = [];
        $this->config['steel']['version'] = "v0.1";
        $this->config['steel']['type'] = "canary";

        $this->config['steel']['autoinclude'] = false;
        $this->config['steel']['useApplication'] = true;
        $this->config['steel']['application'] = ['filepath' => dirname(__FILE__) . '/../../app/Application.php', 'fully_qualified_name' => '\MyCoolApplicationNamespace\MyCoolApplication'];

        $this->config['steel']['useSessions'] = false;

        $this->config['general'] = [];
        $this->config['general']['host'] = 'http://localhost';
    }

    public function getConfig() {
        return $this->config;
    }

}

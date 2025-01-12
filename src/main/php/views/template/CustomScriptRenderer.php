<?php

require_once 'TemplateRenderer.php';

class CustomScriptRenderer extends TemplateRenderer {
    private $scripts;
    private $scriptsModule;

    public function __construct($baseUrl, $scripts, $scriptsModule) {
        parent::__construct($baseUrl);
        $this->scripts = $scripts;
        $this->scriptsModule = $scriptsModule;
    }

    protected function renderCustomScripts() {
        if (!empty($this->scripts)) {
            foreach ($this->scripts as $script) {
                echo "<script src='{$this->baseUrl}src/main/webapp/scripts/{$script}.js'></script>";
            }
        }

        if (!empty($this->scriptsModule)) {
            foreach ($this->scriptsModule as $script) {
                echo "<script type='module' src='{$this->baseUrl}src/main/webapp/scripts/{$script}.js'></script>";
            }
        }
    }
}
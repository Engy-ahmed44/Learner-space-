<?php

abstract class TemplateRenderer {
    protected $baseUrl;

    public function __construct($baseUrl) {
        $this->baseUrl = $baseUrl;
    }

    // Template method
    public function render() {
        $this->renderBaseScripts();
        $this->renderCustomScripts();
    }

    protected function renderBaseScripts() {
        echo "<script src='{$this->baseUrl}src/main/webapp/lib/jquery/jquery-3.4.1.min.js'></script>";
        echo "<script src='{$this->baseUrl}src/main/webapp/lib/bootstrap/bootstrap.bundle.min.js'></script>";
        echo "<script src='{$this->baseUrl}src/main/webapp/lib/scrollbar_light/jquery.mCustomScrollbar.concat.min.js'></script>";
        echo "<script src='{$this->baseUrl}src/main/webapp/lib/scrollbar_light/scrollbar_light.js'></script>";
        echo "<script src='{$this->baseUrl}src/main/webapp/lib/password_strength/ps_script.js'></script>";
        echo "<script src='{$this->baseUrl}src/main/webapp/lib/mask/jquery.mask.js'></script>";
        echo "<script src='{$this->baseUrl}src/main/webapp/scripts/global.js'></script>";
        echo "<script>var BASE_URL = '{$this->baseUrl}'</script>";
    }

    // Abstract method to be implemented by subclasses
    protected abstract function renderCustomScripts();
}
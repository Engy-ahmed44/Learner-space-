<?php
require_once 'TemplateRenderer.php';
require_once 'CustomScriptRenderer.php';

$scriptsModule = $scriptsModule ?? null;
$renderer = new CustomScriptRenderer(BASE_URL, $scripts, $scriptsModule);
$renderer->render();
?>
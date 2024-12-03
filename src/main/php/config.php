<?php
require 'environment.php';


if (ENVIRONMENT == 'development') {
    define("BASE_URL", "http://localhost/");
    define("DB", array(
        'host' => "127.0.0.1",
        'charset' => "utf8",
        'username' => "root",
        'password' => "",
        'database' => "learning_platform"
    ));
}
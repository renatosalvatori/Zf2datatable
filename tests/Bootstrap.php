<?php
ini_set('error_reporting', E_ALL);
ini_set('display_erros',0);

$files = array(
    __DIR__ . '/../../autoload.php',
    __DIR__ . '/../../../init_autoloader.php'
);



foreach ($files as $file) {
    if (file_exists($file)) {
        $loader = require $file;
        break;
    }
}

if (!isset($loader)) {
    throw new \RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}

/* @var $loader \Composer\Autoload\ClassLoader */
//$loader->add('Zf2datatable\\', __DIR__);



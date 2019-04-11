<?php

use PHPStanMagento1\Autoload\Magento\ModuleControllerAutoLoader;

if (!class_exists(Mage::class)) {
    require_once __DIR__ . '/../../../public/app/Mage.php';
}

(new ModuleControllerAutoLoader('local'))->register();

// workaround Magento's use of date phpdoc typehint for string type
// better would be to implement the typehint to make it appear string type
class date {}

Mage::app();

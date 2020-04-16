<?php
declare(strict_types=1);

use PHPStanMagento1\Autoload\Magento\ModuleControllerAutoloader;

(new ModuleControllerAutoloader('local'))->register();
(new ModuleControllerAutoloader('core'))->register();
(new ModuleControllerAutoloader('community'))->register();

// workaround Magento's use of date phpdoc typehint for string type
// better would be to implement the typehint to make it appear string type
class date {}

Mage::app();

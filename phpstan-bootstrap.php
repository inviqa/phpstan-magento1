<?php

if (!class_exists('Mage')) {
    require_once __DIR__ . '/../../../public/app/Mage.php';
}

// workaround Magento's use of date phpdoc typehint for string type
// better would be to implement the typehint to make it appear string type
class date {}

Mage::app();

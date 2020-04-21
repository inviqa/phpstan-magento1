<?php
declare(strict_types=1);

namespace PHPStanMagento1\Type\Mage\CoreModelLayout;

abstract class MethodReturnTypeDetector extends \PHPStanMagento1\Type\Mage\MethodReturnTypeDetector implements \PHPStan\Type\DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return \Mage_Core_Model_Layout::class;
    }
}
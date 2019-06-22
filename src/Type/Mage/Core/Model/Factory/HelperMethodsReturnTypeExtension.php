<?php

namespace PHPStanMagento1\Type\Mage\Core\Model\Factory;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

use Mage;
use Mage_Core_Model_Factory;


class HelperMethodsReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return Mage_Core_Model_Factory::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return in_array(
            $methodReflection->getName(),
            [
                'getModel',
                'getResourceModel',
                'getSingleton',
                'getHelper',
            ]
        );
    }

    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        if (!isset($methodCall->args[0]) || !$methodCall->args[0]->value instanceof String_) {
            throw new ShouldNotHappenException();
        }

        $name = $methodCall->args[0]->value->value;
        $class = $this->getClassFromHelperMethod($methodReflection->getName(), $name);
        return new ObjectType($class);
    }

    private function getClassFromHelperMethod($method, $name)
    {
        $config = Mage::getConfig();
        switch ($method) {
            case 'getModel':
            case 'getSingleton':
                return $config->getModelClassName($name);
            case 'getResourceModel':
                return $config->getResourceModelClassName($name);
            case 'getHelper':
                return $config->getHelperClassName($name);
        }
        throw new ShouldNotHappenException();
    }
}
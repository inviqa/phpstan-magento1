<?php

namespace PHPStanMagento1\Type\Mage;

use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

use Mage;

class HelperMethodsReturnTypeExtension implements DynamicStaticMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return Mage;
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection): bool
    {
        return in_array(
            $methodReflection->getName(),
            [
                'getModel',
                'getResourceModel',
                'getResourceSingleton',
                'getSingleton',
                'helper',
            ]
        );
    }

    public function getTypeFromStaticMethodCall(MethodReflection $methodReflection, StaticCall $methodCall, Scope $scope): Type
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
            case 'getResourceSingleton':
                return $config->getModelClassName($name);
            case 'helper':
                return $config->getHelperClassName($name);
        }
        throw new ShouldNotHappenException();
    }
}
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

/**
 * Class HelperMethodsReturnTypeExtension
 * @package PHPStanMagento1\Type\Mage
 */
class HelperMethodsReturnTypeExtension implements DynamicStaticMethodReturnTypeExtension
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Mage::class;
    }

    /**
     * @param MethodReflection $methodReflection
     * @return bool
     */
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

    /**
     * @param MethodReflection $methodReflection
     * @param StaticCall $methodCall
     * @param Scope $scope
     * @return Type
     * @throws ShouldNotHappenException
     */
    public function getTypeFromStaticMethodCall(MethodReflection $methodReflection, StaticCall $methodCall, Scope $scope): Type
    {
        if (!isset($methodCall->args[0]) || !$methodCall->args[0]->value instanceof String_) {
            throw new ShouldNotHappenException();
        }

        $name = $methodCall->args[0]->value->value;
        $class = $this->getClassFromHelperMethod($methodReflection->getName(), $name);
        return new ObjectType($class);
    }

    /**
     * @param string $method
     * @param string $name
     * @return false|string
     * @throws ShouldNotHappenException
     */
    private function getClassFromHelperMethod($method, $name)
    {
        $config = Mage::getConfig();
        switch ($method) {
            case 'getModel':
            case 'getSingleton':
                return $config->getModelClassName($name);
            case 'getResourceModel':
            case 'getResourceSingleton':
                return $config->getResourceModelClassName($name);
            case 'helper':
                return $config->getHelperClassName($name);
        }
        throw new ShouldNotHappenException();
    }
}
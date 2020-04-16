<?php
declare(strict_types=1);

namespace PHPStanMagento1\Type\Mage;

use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

use Mage;

final class HelperMethodsReturnTypeExtension implements DynamicStaticMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return Mage::class;
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection): bool
    {
        return \in_array(
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
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function getTypeFromStaticMethodCall(MethodReflection $methodReflection, StaticCall $methodCall, Scope $scope): Type
    {
        if (!isset($methodCall->args[0]) || !$methodCall->args[0]->value instanceof String_) {
            throw new ShouldNotHappenException();
        }

        $name = $methodCall->args[0]->value->value;
        $class = $this->getClassFromHelperMethod($methodReflection->getName(), $name);

        if ($class === false) {
            return new NullType();
        }

        return new ObjectType($class);
    }

    /**
     * @throws \PHPStan\ShouldNotHappenException
     */
    private function getClassFromHelperMethod(string $method, string $name)
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
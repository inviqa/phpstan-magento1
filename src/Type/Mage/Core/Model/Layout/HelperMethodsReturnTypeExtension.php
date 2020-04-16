<?php
declare(strict_types=1);

namespace PHPStanMagento1\Type\Mage\Core\Model\Layout;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

use Mage;
use Mage_Core_Model_Layout;

final class HelperMethodsReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return Mage_Core_Model_Layout::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return \in_array(
            $methodReflection->getName(),
            [
                'getBlockSingleton',
                'helper',
            ]
        );
    }

    /**
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        if (!isset($methodCall->args[0]) || !$methodCall->args[0]->value instanceof String_) {
            throw new ShouldNotHappenException();
        }

        $name = $methodCall->args[0]->value->value;
        $class = $this->getClassFromHelperMethod($methodReflection->getName(), $name);
        return new ObjectType($class);
    }

    /**
     * @throws \PHPStan\ShouldNotHappenException
     */
    private function getClassFromHelperMethod(string $method, string $name): string
    {
        $config = Mage::getConfig();
        switch ($method) {
            case 'getBlockSingleton':
                return $config->getBlockClassName($name);
            case 'helper':
                return $config->getHelperClassName($name);
        }

        throw new ShouldNotHappenException();
    }
}
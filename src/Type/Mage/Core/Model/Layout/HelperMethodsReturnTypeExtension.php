<?php

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

/**
 * Class HelperMethodsReturnTypeExtension
 * @package PHPStanMagento1\Type\Mage\Core\Model\Layout
 */
class HelperMethodsReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Mage_Core_Model_Layout::class;
    }

    /**
     * @param MethodReflection $methodReflection
     * @return bool
     */
    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return in_array(
            $methodReflection->getName(),
            [
                'createBlock',
                'getBlockSingleton',
                'helper',
            ]
        );
    }

    /**
     * @param MethodReflection $methodReflection
     * @param MethodCall $methodCall
     * @param Scope $scope
     * @return Type
     * @throws ShouldNotHappenException
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
     * @param string $method
     * @param string $name
     * @return string
     * @throws ShouldNotHappenException
     */
    private function getClassFromHelperMethod($method, $name)
    {
        $config = Mage::getConfig();
        switch ($method) {
            case 'createBlock':
            case 'getBlockSingleton':
                return $config->getBlockClassName($name);
            case 'helper':
                return $config->getHelperClassName($name);
        }
        throw new ShouldNotHappenException();
    }
}
<?php

namespace PHPStanMagento1\Reflection\Varien\Object;

use PHPStan\Reflection\ClassMemberReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\TrivialParametersAcceptor;

/**
 * Class MagicMethodReflection
 * @package PHPStanMagento1\Reflection\Varien\Object
 */
class MagicMethodReflection implements MethodReflection
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var ClassReflection
     */
    private $declaringClass;

    /**
     * MagicMethodReflection constructor.
     * @param ClassReflection $declaringClass
     * @param string $name
     */
    public function __construct(ClassReflection $declaringClass, string $name)
    {
        $this->declaringClass = $declaringClass;
        $this->name = $name;
    }

    /**
     * @return ClassReflection
     */
    public function getDeclaringClass(): ClassReflection
    {
        return $this->declaringClass;
    }

    /**
     * @return ClassMemberReflection
     */
    public function getPrototype(): ClassMemberReflection
    {
        return $this;
    }

    /**
     * @return bool
     */
    public function isStatic(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isPrivate(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

	/**
	 * @return \PHPStan\Reflection\ParametersAcceptor[]
	 */
	public function getVariants(): array
	{
		return [
			new TrivialParametersAcceptor(),
		];
	}
}

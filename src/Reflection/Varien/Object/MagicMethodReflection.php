<?php
declare(strict_types=1);

namespace PHPStanMagento1\Reflection\Varien\Object;

use PHPStan\Reflection\ClassMemberReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\TrivialParametersAcceptor;

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

    public function __construct(ClassReflection $declaringClass, string $name)
    {
        $this->declaringClass = $declaringClass;
        $this->name = $name;
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->declaringClass;
    }

    public function getPrototype(): ClassMemberReflection
    {
        return $this;
    }

    public function isStatic(): bool
    {
        return false;
    }

    public function isPrivate(): bool
    {
        return false;
    }

    public function isPublic(): bool
    {
        return true;
    }

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

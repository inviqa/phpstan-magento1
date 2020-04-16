<?php
declare(strict_types=1);

namespace PHPStanMagento1\Autoload\Magento;

use Mage;
use ReflectionClass;

final class ModuleControllerAutoloader
{
    /** @var string */
    private $magentoRoot;

    /** @var string */
    private $codePool;

    public function __construct(string $codePool, $magentoRoot = null)
    {
        if (empty($magentoRoot)) {
            $mageClass = new ReflectionClass(Mage::class);
            if ($mageClass->getFileName() !== false) {
                $magentoRoot = \dirname($mageClass->getFileName(), 2);
            } else {
                throw new \RuntimeException('Could not find path to Mage class');
            }
        }
        $this->codePool = $codePool;
        $this->magentoRoot = $magentoRoot;
    }

    public function register(): void
    {
        spl_autoload_register([$this, 'autoload']);
    }

    public function autoload($className): void
    {
        if (preg_match('/^([a-zA-Z0-9\x7f-\xff]*)_([a-zA-Z0-9\x7f-\xff]*)_([a-zA-Z0-9_\x7f-\xff]+)/', $className, $match) === 1) {
            $class = str_replace('_', '/', $match[3]);
            $controllerFilename = sprintf('%s/app/code/%s/%s/%s/controllers/%s.php', $this->magentoRoot, $this->codePool, $match[1], $match[2], $class);
            if (file_exists($controllerFilename)) {
                (static function ($file) {
                    include $file;
                })($controllerFilename);
            }
        }
    }
}

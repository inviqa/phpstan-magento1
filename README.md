# phpstan-magento1 extension

Extension for [PHPStan](https://github.com/phpstan/phpstan) to allow analysis of Magento 1 code.

Currently it assumes Magento is installed in the public/ directory of the project root. Further work is needed in phpstan itself to allow more intellegence for extensions to be more customised whilst working also with phpstan/phpstan-shim.

By default phpstan with this extension will test public/app/code/local only.

## Usage

Add `phpstan.neon` to your Magento 1 project.

Make sure it has

```neon
includes:
	- vendor/inviqa/phpstan-magento1/extension.neon
```

Whilst this extension depends on phpstan/phpstan, it can also depend on phpstan/phpstan-shim, which decouples its dependencies from the project's own use of them.

With coupled dependencies:

```bash
composer require inviqa/phpstan-magento2 phpstan/phpstan
```

With uncoupled phar package:

```bash
composer require inviqa/phpstan-magento2 phpstan/phpstan-shim
```

# Known Issues

## Controllers aren't autoloaded

This will be looked at first.

Currently you can workaround this by using phpstan configuration parameters.autoload_directories

## Data/SQL scripts can't be tested

Since these scripts use a presumed $this variable due to being included from a setup class, work is needed to:

* work out the correct setup class
* somehow make phpstan aware of it for the file

## Magento fluent interface classes aren't fluent for sub-classes

This causes subsequent calls to the class object to assume the scope of the super-class that defined the return type.

This is due to their PHPDoc not using the up to date way of specifying fluency with subclasses using "$this" as the type.

Options to resolve:

 * Avoid using the fluent interface on these classes
 * Patch Magento code to use $this as the return type
 * Define @method PHPDoc for the called methods in the subclass
 * Add the error to the parameters.ignoreErrors phpstan configuration to ignore it - this loses the ability to properly type the subseqent methods of the fluent interface

## Mage_Core_Model_Abstract::load $id is not an integer

This is due to an inadequate PHPDoc of the load method of the Mage_Core_Model_Abstract class. It should have used a "mixed" type to support when a field is specified as the 2nd argument.

PHPStan extensions have no way of altering existing method parameters.

Options to resolve:

 * Patch the Magento code to use mixed as the $id parameter type
 * If available in the chain of super-classes, use the loadBy{Field} method, which has the correct parameter typing
 * Define @method PHPDoc in your class (which extends Mage_Core_Model_Abstract or a subclass of it) with the correct parameter types
 * Add the error to the parameters.ignoreErrors phpstan configuration to ignore it

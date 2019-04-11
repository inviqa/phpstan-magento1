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

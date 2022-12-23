# PHPStan PHP Language Extensions (currently in BETA)

[![PHP versions: 8.0 to 8.2](https://img.shields.io/badge/php-8.0|8.1|8.2-blue.svg)](https://packagist.org/packages/dave-liddament/phpstan-php-language-extensions)
[![Latest Stable Version](https://poser.pugx.org/dave-liddament/phphstan-php-language-extensions/v/stable)](https://packagist.org/packages/dave-liddament/phpstan-php-language-extensions)
[![License](https://poser.pugx.org/dave-liddament/phpstan-php-language-extensions/license)](https://github.com/DaveLiddament/phpstan-php-language-extensions/blob/main/LICENSE.md)
[![Total Downloads](https://poser.pugx.org/dave-liddament/phpstan-php-language-extensions/downloads)](https://packagist.org/packages/dave-liddament/phpstan-php-language-extensions/stats)

[![Continuous Integration](https://github.com/DaveLiddament/phpstan-php-language-extensions/workflows/Full%20checks/badge.svg)](https://github.com/DaveLiddament/phpstan-php-language-extensions/actions)
[![Psalm level 1](https://img.shields.io/badge/Psalm-max%20level-brightgreen.svg)](https://github.com/DaveLiddament/phpstan-php-language-extensions/blob/main/psalm.xml)
[![PHPStan level 8](https://img.shields.io/badge/PHPStan-max%20level-brightgreen.svg)](https://github.com/DaveLiddament/phpstan-php-language-extensions/blob/main/phpstan.neon)


This is an extension for [PHPStan](https://phpstan.org) for adding analysis for [PHP Language Extensions](https://github.com/DaveLiddament/php-language-extensions).

**Language feature added:**
- [package](https://github.com/DaveLiddament/php-language-extensions#package) 
- [friend](https://github.com/DaveLiddament/php-language-extensions#friend)

## Installation

To make the attributes available for your codebase use:

```shell
composer require dave-liddament/php-language-extensions
```

To use install PHPStan extension use:

```shell
composer require --dev dave-liddament/phpstan-php-language-extensions
```

If you are using [phpstan/extension-installer](https://github.com/phpstan/extension-installer) you're ready to go (but you might want to check out the configuration options)

<details>
  <summary>Manual installation</summary>

If you don't want to use `phpstan/extension-installer`, include rules.neon in your project's PHPStan config:

```
includes:
    - vendor/dave-liddament/phpstan-php-language-extensions/extension.neon
```
</details>

### Configuring

Some attributes, e.g. `#[package]`, might make testing difficult. It is possible to disable the checks for test code in one of two ways:

#### Exclude checks on class names ending with Test:

To exclude any checks from classes that where the name ends with `Test` add the following to the parameters section of your `phpstan.neon` file:

```yaml
parameters:
  phpLanguageExtensions:
    mode: className
```


#### Exclude checks based on test namespace

To exclude any checks from classes that are in the test namespace (e.g. `Acme\Test`) add the following to the parameters section of your `phpstan.neon` file:

```yaml
parameters:
  phpLanguageExtensions:
    mode: namespace
    testNamespace: 'Acme\Test'
```

## Contributing

See [Contributing](CONTRIBUTING.md).

## Demo project

See [PHP language extensions PHPStan demo](https://github.com/DaveLiddament/php-language-extensions-phpstan-demo) project.

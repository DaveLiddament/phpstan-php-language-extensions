<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\PackageMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractPackageRuleTest<PackageMethodCallRule> */
class PackageOnMethodTest extends AbstractPackageRuleTest
{
    protected function getRule(): Rule
    {
        return new PackageMethodCallRule(
            $this->createReflectionProvider(),
            new TestConfig(),
        );
    }

    public function testMethodCall(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/package/packageOnMethod.php',
            [
                [
                    43,
                    'PackageOnMethod\Person::updateName',
                    'PackageOnMethod2',
                ],
            ],
        );
    }
}

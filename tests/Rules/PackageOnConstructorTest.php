<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\PackageNewCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractPackageRuleTest<PackageNewCallRule> */
class PackageOnConstructorTest extends AbstractPackageRuleTest
{
    protected function getRule(): Rule
    {
        return new PackageNewCallRule(
            $this->createReflectionProvider(),
            new TestConfig(),
        );
    }

    public function testMethodCall(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/package/packageOnConstructor.php',
            [
                [
                    39,
                    'PackageOnConstructor\Person::__construct',
                    'PackageOnConstructor2',
                ],
            ],
        );
    }
}

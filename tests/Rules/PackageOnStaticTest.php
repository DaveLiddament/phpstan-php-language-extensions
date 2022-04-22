<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\PackageStaticCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractPackageRuleTest<PackageStaticCallRule> */
class PackageOnStaticTest extends AbstractPackageRuleTest
{
    protected function getRule(): Rule
    {
        return new PackageStaticCallRule(
            $this->createReflectionProvider(),
            new TestConfig(TestConfig::NONE),
        );
    }

    public function testMethodCall(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/package/packageOnStaticMethod.php',
            [
                [
                    40,
                    'PackageOnStaticMethod\Person::updateName',
                    'PackageOnStaticMethod2',
                ],
            ],
        );
    }
}

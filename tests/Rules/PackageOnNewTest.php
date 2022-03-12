<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\PackageNewCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractPackageRuleTest<PackageNewCallRule> */
class PackageOnNewTest extends AbstractPackageRuleTest
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
            __DIR__.'/data/package/packageOnNew.php',
            [
                [
                    35,
                    'PackageOnNew\Person::__construct',
                    'PackageOnNew2',
                ],
            ],
        );
    }
}

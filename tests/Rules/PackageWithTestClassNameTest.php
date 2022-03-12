<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\PackageMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractPackageRuleTest<PackageMethodCallRule> */
class PackageWithTestClassNameTest extends AbstractPackageRuleTest
{
    protected function getRule(): Rule
    {
        return new PackageMethodCallRule(
            $this->createReflectionProvider(),
            new TestConfig(
                mode: TestConfig::CLASS_NAME,
            ),
        );
    }

    public function testCallsFromTestClass(): void
    {
        $this->assertNoErrorsReported(__DIR__.'/data/package/packageRulesIgnoredForTestClass.php');
    }
}

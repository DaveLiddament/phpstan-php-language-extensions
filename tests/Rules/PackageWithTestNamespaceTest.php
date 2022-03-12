<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\PackageMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractPackageRuleTest<PackageMethodCallRule> */
class PackageWithTestNamespaceTest extends AbstractPackageRuleTest
{
    protected function getRule(): Rule
    {
        return new PackageMethodCallRule(
            $this->createReflectionProvider(),
            new TestConfig(
                mode: TestConfig::NAMESPACE,
                testNamespace: 'PackageRulesIgnoredForTestNamespace\Test'
            ),
        );
    }

    public function testCallsFromTestClass(): void
    {
        $this->assertNoErrorsReported(__DIR__.'/data/package/packageRulesIgnoredForTestNamespace.php');
    }
}

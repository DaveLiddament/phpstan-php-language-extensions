<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\CallableFromMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractPackageRuleTest<CallableFromMethodCallRule> */
class CallableFromWithTestNamespaceTest extends AbstractPackageRuleTest
{
    protected function getRule(): Rule
    {
        return new CallableFromMethodCallRule(
            $this->createReflectionProvider(),
            new TestConfig(
                mode: TestConfig::NAMESPACE,
                testNamespace: 'CallableFromRulesIgnoredForTestNamespace\Test'
            ),
        );
    }

    public function testCallsFromTestClass(): void
    {
        $this->assertNoErrorsReported(__DIR__.'/data/callableFrom/callableFromRulesIgnoredForTestNamespace.php');
    }
}

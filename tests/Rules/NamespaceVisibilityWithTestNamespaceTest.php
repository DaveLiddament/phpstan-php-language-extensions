<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\NamespaceVisibilityMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractNamespaceVisibilityRuleTest<NamespaceVisibilityMethodCallRule> */
class NamespaceVisibilityWithTestNamespaceTest extends AbstractNamespaceVisibilityRuleTest
{
    protected function getRule(): Rule
    {
        return new NamespaceVisibilityMethodCallRule(
            $this->createReflectionProvider(),
            new TestConfig(
                mode: TestConfig::NAMESPACE,
                testNamespace: 'NamespaceVisibilityRulesIgnoredForTestNamespace\Test'
            ),
        );
    }

    public function testCallsFromTestClass(): void
    {
        $this->assertNoErrorsReported(__DIR__.'/data/namespaceVisibility/namespaceVisibilityRulesIgnoredForTestNamespace.php');
    }
}

<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\TestTagNewCallRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;

/** @extends AbstractRuleTestCase<TestTagNewCallRule> */
class TestTagOnConstructorIgnoredInTestNamespaceTest extends AbstractRuleTestCase
{
    protected function getRule(): Rule
    {
        return new TestTagNewCallRule(
            $this->createReflectionProvider(),
            new TestConfig(
                TestConfig::NAMESPACE,
                testNamespace: 'TestTagOnConstructorIgnoredInTestNamespace\Test',
            ),
        );
    }

    public function testMethodCall(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/data/testTag/testTagOnConstructorIgnoredInTestNamespace.php',
        );
    }
}

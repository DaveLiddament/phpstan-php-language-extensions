<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\TestTagMethodCallRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;

/** @extends AbstractRuleTestCase<TestTagMethodCallRule> */
class TestTagOnMethodIgnoredInTestNamespaceTest extends AbstractRuleTestCase
{
    protected function getRule(): Rule
    {
        return new TestTagMethodCallRule(
            $this->createReflectionProvider(),
            new TestConfig(
                TestConfig::NAMESPACE,
                testNamespace: 'TestTagOnMethodIgnoredInTestNamespace\Test',
            ),
        );
    }

    public function testMethodCall(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/data/testTag/testTagOnMethodIgnoredInTestNamespace.php',
        );
    }
}

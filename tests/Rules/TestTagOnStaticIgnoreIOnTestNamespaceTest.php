<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\TestTagStaticCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractTestTagRuleTest<TestTagStaticCallRule> */
class TestTagOnStaticIgnoreIOnTestNamespaceTest extends AbstractTestTagRuleTest
{
    protected function getRule(): Rule
    {
        return new TestTagStaticCallRule(
            $this->createReflectionProvider(),
            new TestConfig(
                TestConfig::NAMESPACE,
                testNamespace: 'TestTagOnStaticMethodIgnoredInTestNamepace\Test',
            ),
        );
    }

    public function testMethodCall(): void
    {
        $this->assertNoErrorsReported(
            __DIR__.'/data/testTag/testTagOnStaticMethodIgnoredInTestNamespace.php',
        );
    }
}

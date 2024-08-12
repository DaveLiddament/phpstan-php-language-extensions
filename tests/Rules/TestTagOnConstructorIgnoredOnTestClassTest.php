<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\TestTagNewCallRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;

/** @extends AbstractRuleTestCase<TestTagNewCallRule> */
class TestTagOnConstructorIgnoredOnTestClassTest extends AbstractRuleTestCase
{
    protected function getRule(): Rule
    {
        return new TestTagNewCallRule(
            $this->createReflectionProvider(),
            new TestConfig(TestConfig::CLASS_NAME),
        );
    }

    public function testMethodCall(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/data/testTag/testTagOnConstructorIgnoredInTestClass.php',
        );
    }
}

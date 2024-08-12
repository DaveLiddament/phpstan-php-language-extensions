<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\TestTagStaticCallRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;

/** @extends AbstractRuleTestCase<TestTagStaticCallRule> */
class TestTagOnStaticIgnoredOnTestClassTest extends AbstractRuleTestCase
{
    protected function getRule(): Rule
    {
        return new TestTagStaticCallRule(
            $this->createReflectionProvider(),
            new TestConfig(TestConfig::CLASS_NAME),
        );
    }

    public function testMethodCall(): void
    {
        $this->assertIssuesReported(__DIR__.'/data/testTag/testTagOnStaticMethodIgnoredInTestClass.php');
    }
}

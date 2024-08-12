<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\TestTagNewCallRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\PhpstanRuleTestHelper\ErrorMessageFormatter;
use PHPStan\Rules\Rule;

/** @extends AbstractRuleTestCase<TestTagNewCallRule> */
class TestTagClassOnConstructorIngoredOnTestClassTest extends AbstractRuleTestCase
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
        $this->assertIssuesReported(__DIR__.'/data/testTag/testTagClassOnConstructorIgnoredInTestClass.php');
    }

    protected function getErrorFormatter(): ErrorMessageFormatter|string
    {
        return 'TestTagClassOnConstructorIgnoredOnTestClass\Person::__construct is a test tag and can only be called from test code';
    }
}

<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\TestTagMethodCallRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\PhpstanRuleTestHelper\ErrorMessageFormatter;
use PHPStan\Rules\Rule;

/** @extends AbstractRuleTestCase<TestTagMethodCallRule> */
class TestTagClassOnMethodTest extends AbstractRuleTestCase
{
    protected function getRule(): Rule
    {
        return new TestTagMethodCallRule(
            $this->createReflectionProvider(),
            new TestConfig(TestConfig::CLASS_NAME),
        );
    }

    public function testMethodCall(): void
    {
        $this->assertIssuesReported(__DIR__.'/data/testTag/testTagClassOnMethod.php');
    }

    protected function getErrorFormatter(): ErrorMessageFormatter|string
    {
        return 'TestTagClassOnMethod\Person::updateName is a test tag and can only be called from test code';
    }
}

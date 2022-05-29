<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\TestTagMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractTestTagRuleTest<TestTagMethodCallRule> */
class TestTagOnMethodIgnoredOnTestClassTest extends AbstractTestTagRuleTest
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
        $this->assertNoErrorsReported(
            __DIR__.'/data/testTag/testTagOnMethodIgnoredInTestClass.php',
        );
    }
}

<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\TestTagNewCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractTestTagRuleTest<TestTagNewCallRule> */
class TestTagOnConstructorTest extends AbstractTestTagRuleTest
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
        $this->assertErrorsReported(
            __DIR__.'/data/testTag/testTagOnConstructor.php',
            [
                [
                    16,
                    'TestTagOnConstructor\Person::__construct',
                ],
                [
                    21,
                    'TestTagOnConstructor\Person::__construct',
                ],
                [
                    29,
                    'TestTagOnConstructor\Person::__construct',
                ],
            ],
        );
    }
}

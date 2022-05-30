<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\TestTagMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractTestTagRuleTest<TestTagMethodCallRule> */
class TestTagOnMethodTest extends AbstractTestTagRuleTest
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
        $this->assertErrorsReported(
            __DIR__.'/data/testTag/testTagOnMethod.php',
            [
                [
                    18,
                    'TestTagOnMethod\Person::updateName',
                ],
                [
                    26,
                    'TestTagOnMethod\Person::updateName',
                ],
                [
                    31,
                    'TestTagOnMethod\Person::updateName',
                ],
            ],
        );
    }
}

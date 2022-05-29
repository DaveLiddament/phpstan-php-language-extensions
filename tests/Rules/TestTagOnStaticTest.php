<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\TestTagStaticCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractTestTagRuleTest<TestTagStaticCallRule> */
class TestTagOnStaticTest extends AbstractTestTagRuleTest
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
        $this->assertErrorsReported(
            __DIR__.'/data/testTag/testTagOnStaticMethod.php',
            [
                [
                    17,
                    'TestTagOnStaticMethod\Person::updateName',
                ],
                [
                    22,
                    'TestTagOnStaticMethod\Person::updateName',
                ],
                [
                    30,
                    'TestTagOnStaticMethod\Person::updateName',
                ],
                [
                    34,
                    'TestTagOnStaticMethod\Person::updateName',
                ],
            ],
        );
    }
}

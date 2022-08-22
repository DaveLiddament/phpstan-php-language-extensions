<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\CallableFromMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractCallableFromRuleTest<CallableFromMethodCallRule> */
class CallableFromRuleMethodCallTest extends AbstractCallableFromRuleTest
{
    protected function getRule(): Rule
    {
        return new CallableFromMethodCallRule(
            $this->createReflectionProvider(),
            new TestConfig(TestConfig::NONE),
        );
    }

    public function testMethodCall(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/callableFrom/callableFromOnMethod.php',
            [
                [
                    26,
                    'CallableFromOnMethod\Person::updateName',
                    'CallableFromOnMethod\Updater',
                    'CallableFromOnMethod\CallableFromUpdater',
                ],
                [
                    31,
                    'CallableFromOnMethod\Person::updateName',
                    '<no class>',
                    'CallableFromOnMethod\CallableFromUpdater',
                ],
            ],
        );
    }

    public function testMultipleCallableFroms(): void
    {
        $this->assertNoErrorsReported(__DIR__.'/data/callableFrom/multipleCallableFrom.php');
    }

    public function testDifferentNamespace(): void
    {
        $this->assertNoErrorsReported(__DIR__.'/data/callableFrom/callableFromOnDifferentNamespace.php');
    }
}

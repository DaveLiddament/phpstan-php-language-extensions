<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\CallableFromMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractCallableFromRuleTest<CallableFromMethodCallRule> */
class CallableFromOnClassTest extends AbstractCallableFromRuleTest
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
            __DIR__.'/data/callableFrom/callableFromOnClass.php',
            [
                [
                    27,
                    'CallableFromOnClass\Person::updateName',
                    'CallableFromOnClass\Updater',
                    'CallableFromOnClass\CallableFromUpdater',
                ],
                [
                    32,
                    'CallableFromOnClass\Person::updateName',
                    '<no class>',
                    'CallableFromOnClass\CallableFromUpdater',
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

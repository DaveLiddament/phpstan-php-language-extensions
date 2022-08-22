<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\CallableFromMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractCallableFromRuleTest<CallableFromMethodCallRule> */
class CallableFromOnInterfaceMethodTest extends AbstractCallableFromRuleTest
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
            __DIR__.'/data/callableFrom/callableFromOnInterfaceMethod.php',
            [
                [
                    38,
                    'CallableFromOnInterfaceMethod\MessageSender::sendMessage',
                    'CallableFromOnInterfaceMethod\Foo',
                    'CallableFromOnInterfaceMethod\MessageSendingService',
                ],
            ],
        );
    }
}

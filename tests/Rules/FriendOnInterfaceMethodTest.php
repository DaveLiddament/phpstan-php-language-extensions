<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\FriendMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractFriendRuleTest<FriendMethodCallRule> */
class FriendOnInterfaceMethodTest extends AbstractFriendRuleTest
{
    protected function getRule(): Rule
    {
        return new FriendMethodCallRule(
            $this->createReflectionProvider(),
            new TestConfig(TestConfig::NONE),
        );
    }

    public function testMethodCall(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/friend/friendOnInterfaceMethod.php',
            [
                [
                    38,
                    'FriendOnInterfaceMethod\MessageSender::sendMessage',
                    'FriendOnInterfaceMethod\Foo',
                    'FriendOnInterfaceMethod\MessageSendingService',
                ],
            ],
        );
    }
}

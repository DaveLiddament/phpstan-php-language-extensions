<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\FriendMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractFriendRuleTest<FriendMethodCallRule> */
class FriendOnClassTest extends AbstractFriendRuleTest
{
    protected function getRule(): Rule
    {
        return new FriendMethodCallRule(
            $this->createReflectionProvider(),
            new TestConfig(),
        );
    }

    public function testMethodCall(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/friend/friendOnClass.php',
            [
                [
                    27,
                    'FriendOnClass\Person::updateName',
                    'FriendOnClass\Updater',
                    'FriendOnClass\FriendUpdater',
                ],
                [
                    32,
                    'FriendOnClass\Person::updateName',
                    '<no class>',
                    'FriendOnClass\FriendUpdater',
                ],
            ],
        );
    }

    public function testMultipleFriends(): void
    {
        $this->assertNoErrorsReported(__DIR__.'/data/friend/multipleFriends.php');
    }

    public function testDifferentNamespace(): void
    {
        $this->assertNoErrorsReported(__DIR__.'/data/friend/friendOnDifferentNamespace.php');
    }
}

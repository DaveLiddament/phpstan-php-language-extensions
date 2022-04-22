<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\FriendMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractFriendRuleTest<FriendMethodCallRule> */
class FriendRuleMethodCallTest extends AbstractFriendRuleTest
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
            __DIR__.'/data/friend/friendOnMethod.php',
            [
                [
                    26,
                    'FriendOnMethod\Person::updateName',
                    'FriendOnMethod\Updater',
                    'FriendOnMethod\FriendUpdater',
                ],
                [
                    31,
                    'FriendOnMethod\Person::updateName',
                    '<no class>',
                    'FriendOnMethod\FriendUpdater',
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

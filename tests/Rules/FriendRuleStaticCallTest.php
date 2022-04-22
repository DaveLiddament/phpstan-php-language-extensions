<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\FriendStaticCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractFriendRuleTest<FriendStaticCallRule> */
class FriendRuleStaticCallTest extends AbstractFriendRuleTest
{
    protected function getRule(): Rule
    {
        return new FriendStaticCallRule(
            $this->createReflectionProvider(),
            new TestConfig(TestConfig::NONE),
        );
    }

    public function testStaticCall(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/friend/friendOnStaticMethod.php',
            [
                [
                    24,
                    'FriendOnStaticMethod\Person::updateName',
                    'FriendOnStaticMethod\Updater',
                    'FriendOnStaticMethod\FriendUpdater',
                ],
                [
                    28,
                    'FriendOnStaticMethod\Person::updateName',
                    '<no class>',
                    'FriendOnStaticMethod\FriendUpdater',
                ],
            ]
        );
    }
}

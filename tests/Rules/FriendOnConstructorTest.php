<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\FriendNewCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractFriendRuleTest<FriendNewCallRule> */
class FriendOnConstructorTest extends AbstractFriendRuleTest
{
    protected function getRule(): Rule
    {
        return new FriendNewCallRule(
            $this->createReflectionProvider(),
            new TestConfig(TestConfig::NONE),
        );
    }

    public function testStaticCall(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/friend/friendOnConstructor.php',
            [
                [
                    29,
                    'FriendOnConstructor\Person::__construct',
                    'FriendOnConstructor\Exam',
                    'FriendOnConstructor\PersonBuilder',
                ],
                [
                    33,
                    'FriendOnConstructor\Person::__construct',
                    '<no class>',
                    'FriendOnConstructor\PersonBuilder',
                ],
            ]
        );
    }
}

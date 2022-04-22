<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\FriendNewCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractFriendRuleTest<FriendNewCallRule> */
class FriendOnNewTest extends AbstractFriendRuleTest
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
            __DIR__.'/data/friend/friendOnNew.php',
            [
                [
                    20,
                    'FriendOnNew\Person::__construct',
                    'FriendOnNew\Exam',
                    'FriendOnNew\PersonBuilder',
                ],
                [
                    24,
                    'FriendOnNew\Person::__construct',
                    '<no class>',
                    'FriendOnNew\PersonBuilder',
                ],
            ]
        );
    }
}

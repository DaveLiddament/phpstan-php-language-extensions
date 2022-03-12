<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\FriendMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractPackageRuleTest<FriendMethodCallRule> */
class FriendWithTestClassNameTest extends AbstractPackageRuleTest
{
    protected function getRule(): Rule
    {
        return new FriendMethodCallRule(
            $this->createReflectionProvider(),
            new TestConfig(
                mode: TestConfig::CLASS_NAME,
            ),
        );
    }

    public function testCallsFromTestClass(): void
    {
        $this->assertNoErrorsReported(__DIR__.'/data/friend/friendRulesIgnoredForTestClass.php');
    }
}

<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\FriendMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractPackageRuleTest<FriendMethodCallRule> */
class FriendWithTestNamespaceTest extends AbstractPackageRuleTest
{
    protected function getRule(): Rule
    {
        return new FriendMethodCallRule(
            $this->createReflectionProvider(),
            new TestConfig(
                mode: TestConfig::NAMESPACE,
                testNamespace: 'FriendRulesIgnoredForTestNamespace\Test'
            ),
        );
    }

    public function testCallsFromTestClass(): void
    {
        $this->assertNoErrorsReported(__DIR__.'/data/friend/friendRulesIgnoredForTestNamespace.php');
    }
}

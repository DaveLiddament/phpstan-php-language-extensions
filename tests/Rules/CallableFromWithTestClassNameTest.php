<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\CallableFromMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractPackageRuleTest<CallableFromMethodCallRule> */
class CallableFromWithTestClassNameTest extends AbstractPackageRuleTest
{
    protected function getRule(): Rule
    {
        return new CallableFromMethodCallRule(
            $this->createReflectionProvider(),
            new TestConfig(
                mode: TestConfig::CLASS_NAME,
            ),
        );
    }

    public function testCallsFromTestClass(): void
    {
        $this->assertNoErrorsReported(__DIR__.'/data/callableFrom/callableFromRulesIgnoredForTestClass.php');
    }
}

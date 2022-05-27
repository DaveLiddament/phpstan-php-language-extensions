<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\TestClassChecker;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\InjectableVersionRule;
use PHPStan\Rules\Rule;

/** @extends AbstractInjectableVersionRuleTest<InjectableVersionRule> */
class InjectableVersionWithTestNamespaceTest extends AbstractInjectableVersionRuleTest
{
    protected function getRule(): Rule
    {
        return new InjectableVersionRule(
            $this->createReflectionProvider(),
            new TestClassChecker(
                new TestConfig(
                    TestConfig::NAMESPACE,
                    'InjectableVersionRulesIgnoredForTestNamespace\Test',
                )
            ),
        );
    }

    public function testOnClass(): void
    {
        $this->assertNoErrorsReported(
            __DIR__.'/data/injectableVersion/InjectableVersionRulesIgnoredForTestNamespace.php',
        );
    }
}

<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\NamespaceVisibilityNewCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractNamespaceVisibilityRuleTest<NamespaceVisibilityNewCallRule> */
class NamespaceVisibilityOnNewTest extends AbstractNamespaceVisibilityRuleTest
{
    protected function getRule(): Rule
    {
        return new NamespaceVisibilityNewCallRule(
            $this->createReflectionProvider(),
            new TestConfig(TestConfig::NONE),
        );
    }

    public function testMethodCall(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/namespaceVisibility/namespaceVisibilityOnNew.php',
            [
                [
                    54,
                    'NamespaceVisibilityOnNew\Person::__construct',
                    'NamespaceVisibilityOnNew',
                    true,
                ],
            ],
        );
    }

    public function testExcludeSubNamespace(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/namespaceVisibility/namespaceVisibilityOnNewExcludeSubNamespaces.php',
            [
                [
                    40,
                    'NamespaceVisibilityOnNewExcludeSubNamespaces\Person::__construct',
                    'NamespaceVisibilityOnNewExcludeSubNamespaces',
                    false,
                ],
            ],
        );
    }

    public function testForDifferentNamespace(): void
    {
        $this->assertNoErrorsReported(
            __DIR__.'/data/namespaceVisibility/namespaceVisibilityOnNewForDifferentNamespaces.php'
        );
    }
}

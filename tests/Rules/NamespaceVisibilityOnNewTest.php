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
                    53,
                    'NamespaceVisibilityOnNew\Person::__construct',
                    'NamespaceVisibilityOnNew',
                    true,
                ],
            ],
        );
    }

    public function testMethodCallSubNamespacesExcluded(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/namespaceVisibility/namespaceVisibilityOnNewExcludeSubNamespaces.php',
            [
                [
                    21,
                    'NamespaceVisibilityOnNewExcludeSubNamespaces\Person::__construct',
                    'NamespaceVisibilityOnNewExcludeSubNamespaces',
                    false,
                ],
            ],
        );
    }

    public function testMethodCallDifferentNamespace(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/namespaceVisibility/namespaceVisibilityOnNewForDifferentNamespaces.php',
            [
                [
                    52,
                    'NamespaceVisibilityOnNewForDifferentNamespaces\Person::__construct',
                    'NamespaceVisibilityOnNewDifferentNamespace',
                    true,
                ],
            ],
        );
    }
}

<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\NamespaceVisibilityNewCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractNamespaceVisibilityRuleTest<NamespaceVisibilityNewCallRule> */
class NamespaceVisibilityOnConstructorTest extends AbstractNamespaceVisibilityRuleTest
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
            __DIR__.'/data/namespaceVisibility/namespaceVisibilityOnConstructor.php',
            [
                [
                    55,
                    'NamespaceVisibilityOnConstructor\Person::__construct',
                    'NamespaceVisibilityOnConstructor',
                    true,
                ],
            ],
        );
    }

    public function testExcludeSubNamespace(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/namespaceVisibility/namespaceVisibilityOnConstructorExcludeSubNamespaces.php',
            [
                [
                    40,
                    'NamespaceVisibilityOnConstructorExcludeSubNamespaces\Person::__construct',
                    'NamespaceVisibilityOnConstructorExcludeSubNamespaces',
                    false,
                ],
            ],
        );
    }

    public function testForDifferentNamespace(): void
    {
        $this->assertNoErrorsReported(
            __DIR__.'/data/namespaceVisibility/namespaceVisibilityOnConstructorForDifferentNamespaces.php'
        );
    }
}

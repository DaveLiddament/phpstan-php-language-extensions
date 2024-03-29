<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\NamespaceVisibilityStaticCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractNamespaceVisibilityRuleTest<NamespaceVisibilityStaticCallRule> */
class NamespaceVisibilityOnStaticTest extends AbstractNamespaceVisibilityRuleTest
{
    protected function getRule(): Rule
    {
        return new NamespaceVisibilityStaticCallRule(
            $this->createReflectionProvider(),
            new TestConfig(TestConfig::NONE),
        );
    }

    public function testMethodCall(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/namespaceVisibility/namespaceVisibilityOnStaticMethod.php',
            [
                [
                    52,
                    'NamespaceVisibilityOnStaticMethod\Person::updateName',
                    'NamespaceVisibilityOnStaticMethod',
                    true,
                ],
            ],
        );
    }

    public function testExcludeSubNamespace(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/namespaceVisibility/namespaceVisibilityOnStaticMethodExcludeSubNamespaces.php',
            [
                [
                    26,
                    'NamespaceVisibilityOnStaticMethodExcludeSubNamespaces\Person::updateName',
                    'NamespaceVisibilityOnStaticMethodExcludeSubNamespaces',
                    false,
                ],
            ],
        );
    }

    public function testForDifferentNamespace(): void
    {
        $this->assertNoErrorsReported(
            __DIR__.'/data/namespaceVisibility/namespaceVisibilityOnStaticMethodForDifferentNamespaces.php',
        );
    }
}

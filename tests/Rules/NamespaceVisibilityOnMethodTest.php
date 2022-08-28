<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\NamespaceVisibilityMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractNamespaceVisibilityRuleTest<NamespaceVisibilityMethodCallRule> */
class NamespaceVisibilityOnMethodTest extends AbstractNamespaceVisibilityRuleTest
{
    protected function getRule(): Rule
    {
        return new NamespaceVisibilityMethodCallRule(
            $this->createReflectionProvider(),
            new TestConfig(TestConfig::NONE),
        );
    }

    public function testMethodCall(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/namespaceVisibility/namespaceVisibilityOnMethod.php',
            [
                [
                    58,
                    'NamespaceVisibilityOnMethod\Person::updateName',
                    'NamespaceVisibilityOnMethod',
                    true,
                ],
            ],
        );
    }

    public function testExcludeSubNamespace(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/namespaceVisibility/namespaceVisibilityOnMethodExcludeSubNamespace.php',
            [
                [
                    32,
                    'NamespaceVisibilityOnMethodExcludeSubNamespace\Person::updateName',
                    'NamespaceVisibilityOnMethodExcludeSubNamespace',
                    false,
                ],
            ],
        );
    }

    public function testForDifferentNamespace(): void
    {
        $this->assertNoErrorsReported(
            __DIR__.'/data/namespaceVisibility/namespaceVisibilityOnMethodForDifferentNamespace.php'
        );
    }
}

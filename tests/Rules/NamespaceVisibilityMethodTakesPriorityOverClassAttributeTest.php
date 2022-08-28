<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\NamespaceVisibilityMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractNamespaceVisibilityRuleTest<NamespaceVisibilityMethodCallRule> */
class NamespaceVisibilityMethodTakesPriorityOverClassAttributeTest extends AbstractNamespaceVisibilityRuleTest
{
    protected function getRule(): Rule
    {
        return new NamespaceVisibilityMethodCallRule(
            $this->createReflectionProvider(),
            new TestConfig(
                mode: TestConfig::NONE,
            ),
        );
    }

    public function testMethodTakesPriorityOverClassAttribute(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/namespaceVisibility/namespaceVisibilityMethodTakesPriorityOverClassAttribute.php',
            [
                [
                    28,
                    'NamespaceVisibilityMethodTakesPriorityOverClassAttribute\Person::updateName',
                    'NamespaceVisibilityMethodTakesPriorityOverClassAttribute\MethodNamespace',
                    true,
                ],
            ],
        );
    }
}

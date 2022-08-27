<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\NamespaceVisibilityMethodCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractNamespaceVisibilityRuleTest<NamespaceVisibilityMethodCallRule> */
class NamespaceVisibilityOnClassTest extends AbstractNamespaceVisibilityRuleTest
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
            __DIR__.'/data/namespaceVisibility/namespaceVisibilityOnClass.php',
            [
                [
                    61,
                    'NamespaceVisibilityOnClass\Person::updateName',
                    'NamespaceVisibilityOnClass',
                    true,
                ],
            ],
        );
    }
}

<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\RestrictTraitToRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;

/** @extends AbstractRuleTestCase<RestrictTraitToRule> */
final class RestrictTraitToTest extends AbstractRuleTestCase
{
    protected function getRule(): Rule
    {
        return new RestrictTraitToRule($this->createReflectionProvider());
    }

    public function testRestrictTraitTo(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/data/restrictTraitTo/restrictTraitTo.php',
        );
    }

    protected function getErrorFormatter(): string
    {
        return 'Trait can only be used on class or child of: {0}';
    }
}

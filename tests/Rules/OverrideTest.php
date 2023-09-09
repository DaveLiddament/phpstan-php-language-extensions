<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\OverrideRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\PhpstanRuleTestHelper\ErrorMessageFormatter;
use PHPStan\Rules\Rule;

/** @extends AbstractRuleTestCase<OverrideRule> */
final class OverrideTest extends AbstractRuleTestCase
{
    protected function getRule(): Rule
    {
        return new OverrideRule();
    }

    public function testOverrideRuleOnClass(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/data/override/overrideOnClass.php',
        );
    }

    public function testOverrideRuleOnInterface(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/data/override/overrideOnInterface.php',
        );
    }

    public function testOverrideRuleRfcExamples(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/data/override/overrideRfcExamples.php',
        );
    }

    public function getErrorFormatter(): ErrorMessageFormatter
    {
        return new OverrideErrorFormatter();
    }
}

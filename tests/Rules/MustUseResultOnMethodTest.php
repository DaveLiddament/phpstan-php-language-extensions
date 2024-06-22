<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\MustUseResultRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\PhpstanRuleTestHelper\ConstantStringErrorMessageFormatter;
use DaveLiddament\PhpstanRuleTestHelper\ErrorMessageFormatter;
use PHPStan\Rules\Rule;

/** @extends AbstractRuleTestCase<MustUseResultRule> */
final class MustUseResultOnMethodTest extends AbstractRuleTestCase
{
    protected function getRule(): Rule
    {
        return new MustUseResultRule();
    }

    public function testMustUseResultRuleOnMethod(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/data/mustUseResult/mustUseResultOnMethod.php',
        );
    }

    protected function getErrorFormatter(): ErrorMessageFormatter
    {
        return new ConstantStringErrorMessageFormatter('Result returned by method must be used');
    }
}

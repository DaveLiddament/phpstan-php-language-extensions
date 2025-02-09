<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\MustUseResultRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;

/** @extends AbstractRuleTestCase<MustUseResultRule> */
final class MustUseResultTest extends AbstractRuleTestCase
{
    protected function getRule(): Rule
    {
        return new MustUseResultRule($this->createReflectionProvider());
    }

    public function testMustUseResultRuleOnMethod(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/data/mustUseResult/mustUseResultOnMethod.php',
        );
    }

    public function testMustUseResultRuleOnStaticMethod(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/data/mustUseResult/mustUseResultOnStaticMethod.php',
        );
    }

    public function testMustUseWithParent(): void
    {
        $this->assertIssuesReported(__DIR__.'/data/mustUseResult/mustUseResultWithParent.php');
    }

    public function testMustUseResultOnExtendedClass(): void
    {
        $this->assertIssuesReported(__DIR__.'/data/mustUseResult/mustUseResultOnExtendedClass.php');
    }

    public function testMustUseResultOnInterface(): void
    {
        $this->assertIssuesReported(__DIR__.'/data/mustUseResult/mustUseResultOnInterface.php');
    }

    protected function getErrorFormatter(): string
    {
        return 'Result returned by method must be used';
    }
}

<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\SealedRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;

/** @extends AbstractRuleTestCase<SealedRule> */
final class SealedTest extends AbstractRuleTestCase
{
    protected function getRule(): Rule
    {
        return new SealedRule($this->createReflectionProvider());
    }

    public function testSealedClasses(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/data/sealed/sealedClasses.php',
        );
    }

    public function testSealedInterfaces(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/data/sealed/sealedInterfaces.php',
        );
    }

    public function testSealedEdgeCases(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/data/sealed/sealedEdgeCases.php',
        );
    }

    protected function getErrorFormatter(): string
    {
        return '{0} can not {1} {2}';
    }
}

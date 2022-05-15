<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @template TRule of Rule
 * @extends RuleTestCase<TRule>
 */
abstract class AbstractInjectableVersionRuleTest extends RuleTestCase
{
    /** @param array<int,array{int,int,class-string,class-string}> $errors */
    final protected function assertErrorsReported(string $file, array $errors): void
    {
        $expectedIssues = [];
        foreach ($errors as list($lineNumber, $argument, $injectedVersion, $injectableVersion)) {
            $expectedIssues[] = [
                "Argument {$argument} has {$injectedVersion} injected, instead use $injectableVersion",
                $lineNumber,
            ];
        }

        $this->analyse([$file], $expectedIssues);
    }

    final protected function assertNoErrorsReported(string $file): void
    {
        $this->analyse([$file], []);
    }
}

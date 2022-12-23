<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @template TRule of Rule
 *
 * @extends RuleTestCase<TRule>
 */
abstract class AbstractTestTagRuleTest extends RuleTestCase
{
    /** @param array<int,array{int,string}> $errors */
    final protected function assertErrorsReported(string $file, array $errors): void
    {
        $expectedIssues = [];
        foreach ($errors as list($lineNumber, $targetMethodName)) {
            $expectedIssues[] = [
                "$targetMethodName is a test tag and can only be called from test code",
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

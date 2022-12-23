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
abstract class AbstractFriendRuleTest extends RuleTestCase
{
    /** @param array<int,array{int,string,string,string}> $errors */
    final protected function assertErrorsReported(string $file, array $errors): void
    {
        $expectedIssues = [];
        foreach ($errors as list($lineNumber, $targetMethodName, $callingClass, $friends)) {
            $expectedIssues[] = [
                "$targetMethodName cannot be called from {$callingClass}, it can only be called from its friend(s): {$friends}",
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

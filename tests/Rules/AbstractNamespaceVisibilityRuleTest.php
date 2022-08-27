<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @template TRule of Rule
 * @extends RuleTestCase<TRule>
 */
abstract class AbstractNamespaceVisibilityRuleTest extends RuleTestCase
{
    /** @param array<int,array{int,string,string, bool}> $errors */
    final protected function assertErrorsReported(string $file, array $errors): void
    {
        $expectedIssues = [];
        foreach ($errors as list($lineNumber, $targetMethodName, $namespace, $subNamespacesAllowed)) {
            $issue = "$targetMethodName has Namespace Visibility, it can only be called from namespace $namespace";
            if ($subNamespacesAllowed) {
                $issue .= " and sub-namespaces of $namespace";
            }
            $expectedIssues[] = [
                $issue,
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

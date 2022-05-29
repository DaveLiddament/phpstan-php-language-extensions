<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PHPStan\Analyser\Scope;

/** @extends  AbstractTestTagRule<New_> */
class TestTagNewCallRule extends AbstractTestTagRule
{
    public function getNodeType(): string
    {
        return New_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->class instanceof Node\Name) {
            return [];
        }

        $className = $scope->resolveName($node->class);
        $error = $this->getErrorOrNull($scope, $className, '__construct');

        return (null === $error) ? [] : [$error];
    }
}

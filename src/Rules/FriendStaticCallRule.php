<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;

/** @extends AbstractFriendRule<StaticCall> */
class FriendStaticCallRule extends AbstractFriendRule
{
    public function getNodeType(): string
    {
        return StaticCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Node\Identifier) {
            return [];
        }
        $methodName = $node->name->name;

        if (!$node->class instanceof Node\Name) {
            return [];
        }
        $className = $node->class->toCodeString();

        $error = $this->getErrorOrNull($scope, $className, $methodName);

        return (null === $error) ? [] : [$error];
    }
}

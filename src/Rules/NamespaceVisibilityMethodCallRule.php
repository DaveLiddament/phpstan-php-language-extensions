<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;

/** @extends AbstractNamespaceVisibilityRule<MethodCall> */
class NamespaceVisibilityMethodCallRule extends AbstractNamespaceVisibilityRule
{
    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Node\Identifier) {
            return [];
        }

        $methodName = $node->name->name;
        $type = $scope->getType($node->var);

        foreach ($type->getReferencedClasses() as $class) {
            $error = $this->getErrorOrNull($scope, $class, $methodName);
            if (null !== $error) {
                return [$error];
            }
        }

        return [];
    }
}

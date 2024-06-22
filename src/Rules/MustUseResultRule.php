<?php

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\AttributeValueReaders\AttributeFinder;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\Cache;
use DaveLiddament\PhpstanPhpLanguageExtensions\MustUseResult;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Node\Stmt\Expression>
 */
final class MustUseResultRule implements Rule
{
    /** @var Cache<bool> */
    private Cache $cache;

    public function __construct()
    {
        $this->cache = new Cache();
    }

    public function getNodeType(): string
    {
        return Node\Stmt\Expression::class;
    }

    /** @param Node\Stmt\Expression $node */
    public function processNode(Node $node, Scope $scope): array
    {
        $expr = $node->expr;
        if (!$expr instanceof Node\Expr\MethodCall) {
            return [];
        }

        $methodNameNode = $expr->name;
        if (!$methodNameNode instanceof Node\Identifier) {
            return [];
        }

        $methodName = $methodNameNode->toLowerString();

        $classReflections = $scope->getType($expr->var)->getObjectClassReflections();

        foreach ($classReflections as $classReflection) {
            $className = $classReflection->getName();
            $fullMethodName = "{$className}::{$methodName}";

            if ($this->cache->hasEntry($fullMethodName)) {
                $mustUseResult = $this->cache->getEntry($fullMethodName);
            } else {
                $mustUseResult = AttributeFinder::hasAttributeOnMethod(
                    $classReflection->getNativeReflection(),
                    $methodName,
                    MustUseResult::class,
                );
                $this->cache->addEntry($fullMethodName, $mustUseResult);
            }

            if ($mustUseResult) {
                return [
                    RuleErrorBuilder::message('Result returned by method must be used')->build(),
                ];
            }
        }

        return [];
    }
}

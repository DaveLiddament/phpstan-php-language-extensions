<?php

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use DaveLiddament\PhpLanguageExtensions\MustUseResult;
use DaveLiddament\PhpstanPhpLanguageExtensions\AttributeValueReaders\AttributeFinder;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\Cache;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Node\Stmt\Expression>
 */
final class MustUseResultRule implements Rule
{
    /** @var Cache<bool> */
    private Cache $cache;

    public function __construct(
        private ReflectionProvider $reflectionProvider,
    ) {
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

        if ($expr instanceof Node\Expr\MethodCall) {
            $classReflections = $scope->getType($expr->var)->getObjectClassReflections();
        } elseif ($expr instanceof Node\Expr\StaticCall) {
            $class = $expr->class;
            if (!$class instanceof Node\Name) {
                return [];
            }

            $className = $scope->resolveName($class);

            $classReflections = [
                $this->reflectionProvider->getClass($className),
            ];
        } else {
            return [];
        }

        $methodNameNode = $expr->name;
        if (!$methodNameNode instanceof Node\Identifier) {
            return [];
        }

        $methodName = $methodNameNode->toLowerString();

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
                    RuleErrorBuilder::message('Result returned by method must be used')
                        ->identifier('phpExtensionLibrary.mustUseResult')
                        ->build(),
                ];
            }
        }

        return [];
    }
}

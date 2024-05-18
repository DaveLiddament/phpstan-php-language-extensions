<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use DaveLiddament\PhpLanguageExtensions\Override;
use DaveLiddament\PhpstanPhpLanguageExtensions\AttributeValueReaders\AttributeFinder;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<InClassNode>
 */
final class OverrideRule implements Rule
{
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /** @param InClassNode $node */
    public function processNode(Node $node, Scope $scope): array
    {
        $methods = $node->getOriginalNode()->getMethods();

        $classReflection = $node->getClassReflection();

        $errors = [];
        foreach ($methods as $method) {
            $methodName = $method->name->toLowerString();

            if (!AttributeFinder::hasAttributeOnMethod(
                $classReflection->getNativeReflection(),
                $methodName,
                Override::class,
            )) {
                continue;
            }

            if ($this->isMethodInAncestor($classReflection, $methodName, $scope)) {
                continue;
            }

            $message = "Method {$methodName} has the Override attribute, but no matching parent method exists";
            $errors[] = RuleErrorBuilder::message($message)->identifier('phpExtensionLibrary.override')->line($method->getLine())->build();
        }

        return $errors;
    }

    private function isMethodInAncestor(ClassReflection $classReflection, string $methodName, Scope $scope): bool
    {
        foreach ($classReflection->getAncestors() as $ancestor) {
            if ($ancestor === $classReflection) {
                continue;
            }

            if ($ancestor->hasMethod($methodName)) {
                $method = $ancestor->getMethod($methodName, $scope);

                if ($method->isPrivate()) {
                    continue;
                }

                return true;
            }
        }

        return false;
    }
}

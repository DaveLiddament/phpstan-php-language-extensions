<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Attributes\InjectableVersion;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/** @implements Rule<ClassMethod> */
class InjectableVersionRule implements Rule
{
    public function __construct(
        private ReflectionProvider $reflectionProvider,
    ) {
    }

    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if ('__construct' !== $node->name->name) {
            return [];
        }

        $errors = [];

        foreach ($node->params as $position => $param) {
            $className = null;
            $type = $param->type;

            if ($type instanceof Node\Name) {
                $className = $scope->resolveName($type);
            }

            if (null === $className) {
                continue;
            }

            $classToUse = $this->checkClass($className);
            if (null !== $classToUse) {
                $message = sprintf(
                    'Argument %d has %s injected, instead use %s',
                    $position + 1,
                    $className,
                    $classToUse
                );

                $errors[] = RuleErrorBuilder::message($message)->build();
            }
        }

        return $errors;
    }

    /*
     * Returns null if OK, otherwise the class that should be injected.
     */
    private function checkClass(string $class): ?string
    {
        $classReflection = $this->reflectionProvider->getClass($class);
        if ($this->isInjectableVersion($classReflection)) {
            return null;
        }

        foreach ($classReflection->getParents() as $parent) {
            if ($this->isInjectableVersion($parent)) {
                return $parent->getName();
            }
        }

        foreach ($classReflection->getInterfaces() as $parent) {
            if ($this->isInjectableVersion($parent)) {
                return $parent->getName();
            }
        }

        return null;
    }

    private function isInjectableVersion(ClassReflection $classReflection): bool
    {
        $nativeReflection = $classReflection->getNativeReflection();

        return count($nativeReflection->getAttributes(InjectableVersion::class)) > 0;
    }
}

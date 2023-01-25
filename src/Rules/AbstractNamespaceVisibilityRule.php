<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;
use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\Cache;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\SubNamespaceChecker;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\TestClassChecker;
use PHPStan\Analyser\Scope;
use PHPStan\BetterReflection\Reflection\Adapter\ReflectionClass;
use PHPStan\BetterReflection\Reflection\Adapter\ReflectionEnum;
use PHPStan\BetterReflection\Reflection\Adapter\ReflectionMethod;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\Constant\ConstantStringType;

/**
 * @template TNodeType of \PhpParser\Node
 *
 * @implements Rule<TNodeType>
 */
abstract class AbstractNamespaceVisibilityRule implements Rule
{
    /**
     * @var Cache<NamespaceVisibilitySetting>
     */
    private Cache $cache;
    private TestClassChecker $testClassChecker;

    final public function __construct(
        private ReflectionProvider $reflectionProvider,
        TestConfig $testConfig,
    ) {
        $this->cache = new Cache();
        $this->testClassChecker = new TestClassChecker($testConfig);
    }

    protected function getErrorOrNull(
        Scope $scope,
        string $class,
        string $methodName,
    ): ?RuleError {
        $classReflection = $this->reflectionProvider->getClass($class);
        $className = $classReflection->getName();
        $nativeReflection = $classReflection->getNativeReflection();
        $classNamespace = $nativeReflection->getNamespaceName();

        $fullMethodName = "{$className}::{$methodName}";

        // Check if method has NamespaceVisibility Attribute
        if ($this->cache->hasEntry($fullMethodName)) {
            $methodNamespaceVisibilitySetting = $this->cache->getEntry($fullMethodName);
        } else {
            if ($nativeReflection->hasMethod($methodName)) {
                $methodReflection = $nativeReflection->getMethod($methodName);
                $methodNamespaceVisibilitySetting = $this->getNamespaceVisibilitiesSettings($methodReflection, $scope, $classNamespace);
            } else {
                $methodNamespaceVisibilitySetting = NamespaceVisibilitySetting::noNamespaceVisibilityAttribute();
            }
            $this->cache->addEntry($fullMethodName, $methodNamespaceVisibilitySetting);
        }

        // If method does not have NamespaceVisibility attribute, see if the class does.
        if ($methodNamespaceVisibilitySetting->hasNamespaceAttribute()) {
            $namespaceVisibilitySetting = $methodNamespaceVisibilitySetting;
        } else {
            if ($this->cache->hasEntry($className)) {
                $classNamespaceVisibilitySetting = $this->cache->getEntry($className);
            } else {
                $classNamespaceVisibilitySetting = $this->getNamespaceVisibilitiesSettings($nativeReflection, $scope, $classNamespace);
                $this->cache->addEntry($className, $classNamespaceVisibilitySetting);
            }

            $namespaceVisibilitySetting = $classNamespaceVisibilitySetting;
        }

        // Exit if neither class nor method have NamespaceVisibility
        if (!$namespaceVisibilitySetting->hasNamespaceAttribute()) {
            return null;
        }

        // If namespaces are same then exit.
        if ($scope->getNamespace() === $namespaceVisibilitySetting->getNamespace()) {
            return null;
        }

        // Check if sub namespace (if allowed)
        $excludeSubNamespaces = $namespaceVisibilitySetting->isExcludeSubNamespaces();
        if (!$excludeSubNamespaces) {
            if (SubNamespaceChecker::isSubNamespace(
                namespace: $scope->getNamespace(),
                namespaceToCheckAgainst: $namespaceVisibilitySetting->getNamespace(),
            )) {
                return null;
            }
        }

        if ($this->testClassChecker->isTestClass($scope->getNamespace(), $scope->getClassReflection()?->getName())) {
            return null;
        }

        $callableFromNamespace = $namespaceVisibilitySetting->getNamespace() ?? '<none>';
        $subNamespaces = (false === $excludeSubNamespaces) ? " and sub-namespaces of {$callableFromNamespace}" : '';
        $message = sprintf(
            '%s has Namespace Visibility, it can only be called from namespace %s%s',
            $fullMethodName,
            $callableFromNamespace,
            $subNamespaces,
        );

        return RuleErrorBuilder::message($message)->build();
    }

    /** @param ReflectionMethod|ReflectionClass|ReflectionEnum $reflection */
    private function getNamespaceVisibilitiesSettings(
        $reflection,
        Scope $scope,
        ?string $namespace,
    ): NamespaceVisibilitySetting {
        $attributes = $reflection->getAttributes(NamespaceVisibility::class);
        if (1 !== count($attributes)) {
            return NamespaceVisibilitySetting::noNamespaceVisibilityAttribute();
        }

        $attribute = $attributes[0];

        $arguments = $attribute->getArgumentsExpressions();
        $excludesSubNamespace = false;
        $excludesSubNamespacesExpr = $arguments['excludeSubNamespaces'] ?? null;
        if (null !== $excludesSubNamespacesExpr) {
            $excludedSubNamespaceType = $scope->getType($excludesSubNamespacesExpr);
            if ($excludedSubNamespaceType instanceof ConstantBooleanType) {
                $excludesSubNamespace = $excludedSubNamespaceType->getValue();
            }
        }

        $namespaceExpr = $arguments['namespace'] ?? null;
        if (null !== $namespaceExpr) {
            $namespaceType = $scope->getType($namespaceExpr);
            if ($namespaceType instanceof ConstantStringType) {
                $namespace = $namespaceType->getValue();
            }
        }

        return NamespaceVisibilitySetting::namespaceVisibility($namespace, $excludesSubNamespace);
    }
}

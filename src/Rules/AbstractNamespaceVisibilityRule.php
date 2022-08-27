<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;
use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\Cache;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\SubNamespaceChecker;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\TestClassChecker;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @template TNodeType of \PhpParser\Node
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

        $fullMethodName = "{$className}::{$methodName}";

        // Check if method has NamespaceVisibility Attribute
        if ($this->cache->hasEntry($fullMethodName)) {
            $methodNamespaceVisibilitySetting = $this->cache->getEntry($fullMethodName);
        } else {
            if ($nativeReflection->hasMethod($methodName)) {
                $methodReflection = $nativeReflection->getMethod($methodName);
                if (count($methodReflection->getAttributes(NamespaceVisibility::class)) > 0) {
                    $methodNamespaceVisibilitySetting = NamespaceVisibilitySetting::namespaceVisibility(
                        $nativeReflection->getNamespaceName(),
                        false
                    );
                } else {
                    $methodNamespaceVisibilitySetting = NamespaceVisibilitySetting::noNamespaceVisibilityAttribute();
                }
            } else {
                $methodNamespaceVisibilitySetting = NamespaceVisibilitySetting::noNamespaceVisibilityAttribute();
            }
            $this->cache->addEntry($fullMethodName, $methodNamespaceVisibilitySetting);
        }


        if ($methodNamespaceVisibilitySetting->hasNamespaceAttribute()) {
            $namespaceVisibilitySetting = $methodNamespaceVisibilitySetting;
        } else {

            if ($this->cache->hasEntry($className)) {
                $classNamespaceVisibilitySetting = $this->cache->getEntry($className);
            } else {
                if (count($nativeReflection->getAttributes(NamespaceVisibility::class)) > 0) {
                    $classNamespaceVisibilitySetting = NamespaceVisibilitySetting::namespaceVisibility(
                        $nativeReflection->getNamespaceName(),
                        false,
                    );
                } else {
                    $classNamespaceVisibilitySetting = NamespaceVisibilitySetting::noNamespaceVisibilityAttribute();
                }
                $this->cache->addEntry($className, $classNamespaceVisibilitySetting);
            }

            $namespaceVisibilitySetting = $classNamespaceVisibilitySetting;
        }


        if (!$namespaceVisibilitySetting->hasNamespaceAttribute()) {
            return null;
        }

        // Check namespaces match
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
        $subNamespaces =  ($excludeSubNamespaces === false) ? " and sub namespaces of {$callableFromNamespace}" : '';
        $message = sprintf(
            '%s has Namespace Visibility, it can only be called from namespace %s%s',
            $fullMethodName,
            $callableFromNamespace,
            $subNamespaces,
        );

        return RuleErrorBuilder::message($message)->build();
    }
}

<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;
use DaveLiddament\PhpstanPhpLanguageExtensions\AttributeValueReaders\AttributeFinder;
use DaveLiddament\PhpstanPhpLanguageExtensions\AttributeValueReaders\AttributeValueReader;
use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\Cache;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\SubNamespaceChecker;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\TestClassChecker;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

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
    ): ?IdentifierRuleError {
        $classReflection = $this->reflectionProvider->getClass($class);
        $className = $classReflection->getName();
        $nativeReflection = $classReflection->getNativeReflection();
        $classNamespace = $nativeReflection->getNamespaceName();

        $fullMethodName = "{$className}::{$methodName}";

        // Check if method has NamespaceVisibility Attribute
        if ($this->cache->hasEntry($fullMethodName)) {
            $methodNamespaceVisibilitySetting = $this->cache->getEntry($fullMethodName);
        } else {
            $attribute = AttributeFinder::getAttributeOnMethod($nativeReflection, $methodName, NamespaceVisibility::class);
            $methodNamespaceVisibilitySetting = $this->getNamespaceVisibilitiesSettings($attribute, $classNamespace);
            $this->cache->addEntry($fullMethodName, $methodNamespaceVisibilitySetting);
        }

        // If method does not have NamespaceVisibility attribute, see if the class does.
        if ($methodNamespaceVisibilitySetting->hasNamespaceAttribute()) {
            $namespaceVisibilitySetting = $methodNamespaceVisibilitySetting;
        } else {
            if ($this->cache->hasEntry($className)) {
                $classNamespaceVisibilitySetting = $this->cache->getEntry($className);
            } else {
                $attribute = AttributeFinder::getAttributeOnClass($nativeReflection, NamespaceVisibility::class);
                $classNamespaceVisibilitySetting = $this->getNamespaceVisibilitiesSettings($attribute, $classNamespace);
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

        return RuleErrorBuilder::message($message)->identifier('phpExtensionLibrary.namespace')->build();
    }

    /**
     * @param \ReflectionAttribute<object>|null $attribute
     */
    private function getNamespaceVisibilitiesSettings(
        ?\ReflectionAttribute $attribute,
        ?string $namespace,
    ): NamespaceVisibilitySetting {
        if (null === $attribute) {
            return NamespaceVisibilitySetting::noNamespaceVisibilityAttribute();
        }

        return NamespaceVisibilitySetting::namespaceVisibility(
            AttributeValueReader::getString($attribute, 0, 'namespace') ?? $namespace,
            AttributeValueReader::getBool($attribute, 1, 'excludeSubNamespaces') ?? false,
        );
    }
}

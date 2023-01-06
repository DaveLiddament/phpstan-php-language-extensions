<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use DaveLiddament\PhpLanguageExtensions\Package;
use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\Cache;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\TestClassChecker;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @template TNodeType of \PhpParser\Node
 *
 * @implements Rule<TNodeType>
 */
abstract class AbstractPackageRule implements Rule
{
    /**
     * @var Cache<bool>
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

        if ($this->cache->hasEntry($fullMethodName)) {
            $isMethodPackageLevel = $this->cache->getEntry($fullMethodName);
        } else {
            if ($nativeReflection->hasMethod($methodName)) {
                $methodReflection = $nativeReflection->getMethod($methodName);
                $isMethodPackageLevel = count($methodReflection->getAttributes(Package::class)) > 0;
            } else {
                $isMethodPackageLevel = false;
            }
            $this->cache->addEntry($fullMethodName, $isMethodPackageLevel);
        }

        if ($this->cache->hasEntry($className)) {
            $isClassPackageLevel = $this->cache->getEntry($className);
        } else {
            $isClassPackageLevel = count($nativeReflection->getAttributes(Package::class)) > 0;
            $this->cache->addEntry($className, $isClassPackageLevel);
        }

        $isPackageLevel = $isClassPackageLevel || $isMethodPackageLevel;

        if (!$isPackageLevel) {
            return null;
        }

        // Check namespaces match
        if ($scope->getNamespace() === $nativeReflection->getNamespaceName()) {
            return null;
        }

        if ($this->testClassChecker->isTestClass($scope->getNamespace(), $scope->getClassReflection()?->getName())) {
            return null;
        }

        $message = sprintf(
            '%s has package visibility and cannot be called from namespace %s',
            $fullMethodName,
            $scope->getNamespace() ?? '<none>'
        );

        return RuleErrorBuilder::message($message)->build();
    }
}

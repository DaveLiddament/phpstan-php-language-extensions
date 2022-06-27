<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use function count;
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

        $isMethodPackageLevel = $this->cache->get(
            $fullMethodName,
            static fn (): bool => $nativeReflection->hasMethod($methodName)
                && count($nativeReflection->getMethod($methodName)->getAttributes(Package::class)) > 0
        );

        $isClassPackageLevel = $this->cache->get(
            $className,
            static fn (): bool => count($nativeReflection->getAttributes(Package::class)) > 0
        );

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

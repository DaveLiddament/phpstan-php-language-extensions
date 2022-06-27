<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use function count;
use DaveLiddament\PhpLanguageExtensions\TestTag;
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
abstract class AbstractTestTagRule implements Rule
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

        $isTestTag = $this->cache->get(
            $fullMethodName,
            static fn (): bool => $nativeReflection->hasMethod($methodName)
                && count($nativeReflection->getMethod($methodName)->getAttributes(TestTag::class)) > 0
        );

        if (!$isTestTag) {
            return null;
        }

        if ($this->testClassChecker->isTestClass($scope->getNamespace(), $scope->getClassReflection()?->getName())) {
            return null;
        }

        $message = sprintf(
            '%s is a test tag and can only be called from test code',
            $fullMethodName,
        );

        return RuleErrorBuilder::message($message)->build();
    }
}

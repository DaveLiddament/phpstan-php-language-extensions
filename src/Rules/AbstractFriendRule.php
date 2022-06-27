<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use DaveLiddament\PhpLanguageExtensions\Friend;
use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\AttributeValueReader;
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
abstract class AbstractFriendRule implements Rule
{
    /**
     * @var Cache<array<int,string>>
     */
    private Cache $cache;
    private TestClassChecker $testClassChecker;

    final public function __construct(
        private ReflectionProvider $reflectionProvider,
        private TestConfig $testConfig,
    ) {
        $this->cache = new Cache();
        $this->testClassChecker = new TestClassChecker($this->testConfig);
    }

    protected function getErrorOrNull(
        Scope $scope,
        string $class,
        string $methodName,
    ): ?RuleError {
        $callingClass = $scope->getClassReflection()?->getName();
        $classReflection = $this->reflectionProvider->getClass($class);
        $className = $classReflection->getName();
        $fullMethodName = "{$className}::{$methodName}";

        $allowedCallingClassesFromMethod = $this->cache->get(
            $fullMethodName,
            static fn (): array => AttributeValueReader::getAttributeValuesForMethod(
                $classReflection->getNativeReflection(),
                $methodName,
                Friend::class
            )
        );

        $allowedCallingClassesFromClass = $this->cache->get(
            $className,
            static fn (): array => AttributeValueReader::getAttributeValuesForClass(
                $classReflection->getNativeReflection(),
                Friend::class
            )
        );

        $allowedCallingClasses = array_merge($allowedCallingClassesFromClass, $allowedCallingClassesFromMethod);

        if ([] === $allowedCallingClasses) {
            return null;
        }

        if ($callingClass === $className) {
            return null;
        }

        if (in_array($callingClass, $allowedCallingClasses, true)) {
            return null;
        }

        if ($this->testClassChecker->isTestClass($scope->getNamespace(), $scope->getClassReflection()?->getName())) {
            return null;
        }

        $message = sprintf(
            '%s cannot be called from %s, it can only be called from its friend(s): %s',
            $fullMethodName,
            $callingClass ?? '<no class>',
            implode('|', $allowedCallingClasses)
        );

        return RuleErrorBuilder::message($message)->build();
    }
}

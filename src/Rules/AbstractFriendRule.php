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
 *
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

        if ($this->cache->hasEntry($fullMethodName)) {
            $allowedCallingClassesFromMethod = $this->cache->getEntry($fullMethodName);
        } else {
            $allowedCallingClassesFromMethod = AttributeValueReader::getAttributeValuesForMethod(
                $classReflection->getNativeReflection(),
                $methodName,
                Friend::class
            );
            $this->cache->addEntry($fullMethodName, $allowedCallingClassesFromMethod);
        }

        if ($this->cache->hasEntry($className)) {
            $allowedCallingClassesFromClass = $this->cache->getEntry($className);
        } else {
            $allowedCallingClassesFromClass = AttributeValueReader::getAttributeValuesForClass(
                $classReflection->getNativeReflection(),
                Friend::class
            );
            $this->cache->addEntry($className, $allowedCallingClassesFromClass);
        }

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

        return RuleErrorBuilder::message($message)->identifier('phpExtensionLibrary.friend')->build();
    }
}

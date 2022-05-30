<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use DaveLiddament\PhpLanguageExtensions\CheckInjectableVersion;
use DaveLiddament\PhpLanguageExtensions\InjectableVersion;
use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\Cache;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\TestClassChecker;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/** @implements Rule<InClassMethodNode> */
class InjectableVersionRule implements Rule
{
    /** @var Cache<?string> */
    private Cache $cache;

    private TestClassChecker $testClassChecker;

    public function __construct(
        private ReflectionProvider $reflectionProvider,
        TestConfig $testConfig,
    ) {
        $this->cache = new Cache();
        $this->testClassChecker = new TestClassChecker($testConfig);
    }

    public function getNodeType(): string
    {
        return InClassMethodNode::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $errors = [];

        $method = $scope->getFunction();

        if (!$method instanceof MethodReflection) {
            // Should never happen
            return [];
        }

        if (!$this->checkMethod($scope->getClassReflection(), $method)) {
            return [];
        }

        if ($this->testClassChecker->isTestClass(
            $scope->getNamespace(),
            $scope->getClassReflection()?->getName()
        )) {
            return [];
        }

        $parameters = ParametersAcceptorSelector::selectSingle($method->getVariants());

        foreach ($parameters->getParameters() as $index => $parameter) {
            $position = $index + 1;

            $type = $parameter->getType();
            $classesToCheck = $type->isIterable()->yes()
                ? $type->getIterableValueType()->getReferencedClasses()
                : $type->getReferencedClasses();

            foreach ($classesToCheck as $className) {
                if ($this->cache->hasEntry($className)) {
                    $classToUse = $this->cache->getEntry($className);
                } else {
                    $classToUse = $this->checkClass($className);
                    $this->cache->addEntry($className, $classToUse);
                }

                if (null !== $classToUse) {
                    $message = sprintf(
                        'Argument %d has %s injected, instead use %s',
                        $position,
                        $className,
                        $classToUse
                    );

                    $errors[] = RuleErrorBuilder::message($message)->build();
                }
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

    private function checkMethod(
        ?ClassReflection $classReflection,
        MethodReflection $method,
    ): bool {
        $methodName = $method->getName();

        // If constructor then check
        if ('__construct' === $methodName) {
            return true;
        }

        if (null === $classReflection) {
            // Should never happen
            return false;
        }

        $nativeClassReflection = $classReflection->getNativeReflection();
        $attributes = $nativeClassReflection->getMethod($methodName)->getAttributes(CheckInjectableVersion::class);

        return count($attributes) > 0;
    }
}

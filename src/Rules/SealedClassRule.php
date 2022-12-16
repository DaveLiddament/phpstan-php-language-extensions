<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use DaveLiddament\PhpLanguageExtensions\Friend;
use DaveLiddament\PhpLanguageExtensions\Sealed;
use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\AttributeValueReader;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\Cache;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\TestClassChecker;
use PhpParser\Node;
use PhpParser\NodeAbstract;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;

/**
 * @implements Rule<Node\Stmt\Class_>
 */
final class SealedClassRule implements Rule
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

    public function getNodeType(): string
    {
        return Node\Stmt\Class_::class;
    }

    /** @param Node\Stmt\Class_ $node */
    public function processNode(Node $node, Scope $scope): array
    {
        $className = $this->reflectionProvider->getClass($node->name->name)->getName();

        $extends = $node->extends;
        if ($extends === null) {
            return [];
        }

        $extendsClassName = $this->reflectionProvider->getClass($extends->toCodeString())->getName();
        var_dump($extendsClassName);

        $extendedClassNativeReflection = $this->reflectionProvider->getClass($extendsClassName)->getNativeReflection();
        $sealedClassNames = AttributeValueReader::getAttributeValuesForClass($extendedClassNativeReflection, Sealed::class);

        if ($sealedClassNames === null) {
            return [];
        }

        foreach($sealedClassNames as $sealedClassName) {
            if ($className === $sealedClassName) {
                return [];
            }
        }

        $message = sprintf(
            "Class [%s] extends [%s], which is sealed and can only be extended by [%s]",
            $className,
            $extendsClassName,
            implode("|", $sealedClassNames)
        );

        return [
            RuleErrorBuilder::message($message)->build(),
        ];
    }
}

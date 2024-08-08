<?php

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use DaveLiddament\PhpLanguageExtensions\RestrictTraitTo;
use DaveLiddament\PhpstanPhpLanguageExtensions\AttributeValueReaders\AttributeFinder;
use DaveLiddament\PhpstanPhpLanguageExtensions\AttributeValueReaders\AttributeValueReader;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\Cache;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;

/** @implements Rule<Node\Stmt\TraitUse> */
final class RestrictTraitToRule implements Rule
{
    /**
     * @var Cache<string|null>
     */
    private Cache $cache;

    public function __construct(
        private ReflectionProvider $reflectionProvider,
    ) {
        $this->cache = new Cache();
    }

    public function getNodeType(): string
    {
        return Node\Stmt\TraitUse::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $containingClassName = $scope->getClassReflection()?->getName();

        if (null === $containingClassName) {
            return [];
        }

        $containingClassObjectType = new ObjectType($containingClassName);

        foreach ($node->traits as $trait) {
            $classReflection = $this->reflectionProvider->getClass($trait->toCodeString())->getNativeReflection();

            if ($this->cache->hasEntry($classReflection)) {
                $restrictTraitToClassName = $this->cache->getEntry($classReflection);
            } else {
                $restrictTraitTo = AttributeFinder::getAttributeOnClass($classReflection, RestrictTraitTo::class);

                if (null === $restrictTraitTo) {
                    $restrictTraitToClassName = null;
                } else {
                    $restrictTraitToClassName = AttributeValueReader::getString($restrictTraitTo, 0, 'className');
                }

                $this->cache->addEntry($classReflection, $restrictTraitToClassName);
            }

            if (null === $restrictTraitToClassName) {
                continue;
            }

            $restrictTraitToObjectType = new ObjectType($restrictTraitToClassName);

            if (!$restrictTraitToObjectType->isSuperTypeOf($containingClassObjectType)->yes()) {
                return [
                    RuleErrorBuilder::message("Trait can only be used on class or child of: $restrictTraitToClassName")
                        ->identifier('phpExtensionLibrary.restrictTraitTo')
                        ->build(),
                ];
            }
        }

        return [];
    }
}

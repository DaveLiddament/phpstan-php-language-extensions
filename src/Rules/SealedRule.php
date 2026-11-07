<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use DaveLiddament\PhpLanguageExtensions\Sealed;
use DaveLiddament\PhpstanPhpLanguageExtensions\AttributeValueReaders\AttributeFinder;
use DaveLiddament\PhpstanPhpLanguageExtensions\AttributeValueReaders\AttributeValueReader;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\Cache;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/** @implements Rule<InClassNode> */
final class SealedRule implements Rule
{
    /**
     * Lowercase names of the classes permitted to extend/implement the key class, or null if it is not sealed.
     *
     * @var Cache<list<string>|null>
     */
    private Cache $cache;

    public function __construct(
        private ReflectionProvider $reflectionProvider,
    ) {
        $this->cache = new Cache();
    }

    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $classLike = $node->getOriginalNode();
        $classReflection = $node->getClassReflection();

        $extends = [];
        $implements = [];
        if ($classLike instanceof Node\Stmt\Class_) {
            $extends = null === $classLike->extends ? [] : [$classLike->extends];
            $implements = $classLike->implements;
        } elseif ($classLike instanceof Node\Stmt\Interface_) {
            $extends = $classLike->extends;
        } elseif ($classLike instanceof Node\Stmt\Enum_) {
            $implements = $classLike->implements;
        }

        $className = $classReflection->isAnonymous() ? 'Anonymous class' : $classReflection->getName();

        $errors = [];
        foreach (['extend' => $extends, 'implement' => $implements] as $relationship => $parents) {
            foreach ($parents as $parent) {
                $parentName = $parent->toString();
                $permitted = $this->getPermitted($parentName);

                if (null === $permitted || in_array(strtolower($className), $permitted, true)) {
                    continue;
                }

                $errors[] = RuleErrorBuilder::message("{$className} can not {$relationship} {$parentName}")
                    ->identifier('phpExtensionLibrary.sealed')
                    ->line($parent->getLine())
                    ->build();
            }
        }

        return $errors;
    }

    /** @return list<string>|null */
    private function getPermitted(string $className): ?array
    {
        if ($this->cache->hasEntry($className)) {
            return $this->cache->getEntry($className);
        }

        $permitted = null;
        if ($this->reflectionProvider->hasClass($className)) {
            $sealed = AttributeFinder::getAttributeOnClass(
                $this->reflectionProvider->getClass($className)->getNativeReflection(),
                Sealed::class,
            );

            if (null !== $sealed) {
                $permitted = array_map('strtolower', AttributeValueReader::getStrings($sealed));
            }
        }

        $this->cache->addEntry($className, $permitted);

        return $permitted;
    }
}

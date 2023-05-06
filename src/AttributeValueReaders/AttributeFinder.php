<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\AttributeValueReaders;

final class AttributeFinder
{
    /**
     * @template T of object
     *
     * @param \ReflectionClass<object>|\ReflectionEnum<object> $class
     * @param class-string<T> $attributeName
     *
     * @return \ReflectionAttribute<T>|null
     */
    public static function getAttributeOnClass(
        \ReflectionClass|\ReflectionEnum $class,
        string $attributeName,
    ): ?\ReflectionAttribute {
        $attributes = $class->getAttributes($attributeName);
        if (1 !== count($attributes)) {
            return null;
        }

        return $attributes[0];
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $attributeName
     * @param \ReflectionClass<object>|\ReflectionEnum<object> $class
     *
     * @return \ReflectionAttribute<T>|null
     */
    public static function getAttributeOnMethod(
        \ReflectionClass|\ReflectionEnum $class,
        string $methodName,
        string $attributeName,
    ): ?\ReflectionAttribute {
        if (!$class->hasMethod($methodName)) {
            return null;
        }

        $method = $class->getMethod($methodName);

        $attributes = $method->getAttributes($attributeName);
        if (1 !== count($attributes)) {
            return null;
        }

        return $attributes[0];
    }

    /**
     * @param class-string $attributeName
     * @param \ReflectionClass<object>|\ReflectionEnum<object> $class
     */
    public static function hasAttributeOnClass(
        \ReflectionClass|\ReflectionEnum $class,
        string $attributeName,
    ): bool {
        $attributes = $class->getAttributes($attributeName);

        return 1 === count($attributes);
    }

    /**
     * @param class-string $attributeName
     * @param \ReflectionClass<object>|\ReflectionEnum<object> $class
     */
    public static function hasAttributeOnMethod(
        \ReflectionClass|\ReflectionEnum $class,
        string $methodName,
        string $attributeName,
    ): bool {
        if (!$class->hasMethod($methodName)) {
            return false;
        }

        $method = $class->getMethod($methodName);

        $attributes = $method->getAttributes($attributeName);

        return 1 === count($attributes);
    }
}

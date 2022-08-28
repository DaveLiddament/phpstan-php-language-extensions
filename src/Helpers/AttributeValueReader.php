<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Helpers;

class AttributeValueReader
{
    /**
     * @param \ReflectionClass<object> $reflectionClass
     * @param class-string $attributeClassName
     *
     * @return array<int, string>|null
     */
    public static function getAttributeValuesForMethod(
        \ReflectionClass $reflectionClass,
        string $methodName,
        string $attributeClassName,
    ): ?array {
        if (!$reflectionClass->hasMethod($methodName)) {
            return null;
        }
        $reflectionMethod = $reflectionClass->getMethod($methodName);

        foreach ($reflectionMethod->getAttributes($attributeClassName) as $attribute) {
            return $attribute->getArguments();
        }

        return null;
    }

    /**
     * @param \ReflectionClass<object> $reflectionClass
     * @param class-string $attributeClassName
     *
     * @return array<int|string, string>|null
     */
    public static function getAttributeValuesForClass(
        \ReflectionClass $reflectionClass,
        string $attributeClassName,
    ): ?array {
        foreach ($reflectionClass->getAttributes($attributeClassName) as $attribute) {
            return $attribute->getArguments();
        }

        return null;
    }
}

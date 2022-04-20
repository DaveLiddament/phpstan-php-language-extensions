<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Helpers;

class AttributeValueReader
{
    /**
     * @param \ReflectionClass<object> $reflectionClass
     * @param class-string $attributeClassName
     *
     * @return array<int, string>
     */
    public static function getAttributeValuesForMethod(
        \ReflectionClass $reflectionClass,
        string $methodName,
        string $attributeClassName,
    ): array {
        if (!$reflectionClass->hasMethod($methodName)) {
            return [];
        }
        $reflectionMethod = $reflectionClass->getMethod($methodName);

        foreach ($reflectionMethod->getAttributes($attributeClassName) as $attribute) {
            return $attribute->getArguments();
        }

        return [];
    }

    /**
     * @param \ReflectionClass<object> $reflectionClass
     * @param class-string $attributeClassName
     *
     * @return array<int, string>
     */
    public static function getAttributeValuesForClass(
        \ReflectionClass $reflectionClass,
        string $attributeClassName,
    ): array {
        foreach ($reflectionClass->getAttributes($attributeClassName) as $attribute) {
            return $attribute->getArguments();
        }

        return [];
    }
}

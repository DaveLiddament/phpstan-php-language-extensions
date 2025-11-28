<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Helpers;

class AttributeValueReader
{
    /**
     * @param \ReflectionClass<*> $reflectionClass
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
            $arguments = $attribute->getArguments();
            Assert::arrayOfStrings($arguments);

            return $arguments;
        }

        return [];
    }

    /**
     * @param \ReflectionClass<*> $reflectionClass
     * @param class-string $attributeClassName
     *
     * @return array<int, string>
     */
    public static function getAttributeValuesForClass(
        \ReflectionClass $reflectionClass,
        string $attributeClassName,
    ): array {
        foreach ($reflectionClass->getAttributes($attributeClassName) as $attribute) {
            $arguments = $attribute->getArguments();
            Assert::arrayOfStrings($arguments);

            return $arguments;
        }

        return [];
    }
}

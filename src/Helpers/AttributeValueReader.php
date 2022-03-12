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
            $argumentCount = count($attribute->getArguments());
            if (1 !== $argumentCount) {
                throw new \LogicException('Friend has no class');
            }

            $value = $attribute->getArguments()[0];

            return (is_string($value)) ? [$value] : $value;
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
            $argumentCount = count($attribute->getArguments());
            if (1 !== $argumentCount) {
                throw new \LogicException('Friend has no class');
            }

            $value = $attribute->getArguments()[0];

            return (is_string($value)) ? [$value] : $value;
        }

        return [];
    }
}

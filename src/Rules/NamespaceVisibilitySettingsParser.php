<?php

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\AttributeValueReader;

class NamespaceVisibilitySettingsParser
{
    /** @param \ReflectionClass<object> $reflectionClass */
    public static function getValuesFromClass(
        \ReflectionClass $reflectionClass
    ): NamespaceVisibilitySetting {
        $values = AttributeValueReader::getAttributeValuesForClass(
            $reflectionClass,
            NamespaceVisibility::class,
        );

        return self::parseValues($values, $reflectionClass->getNamespaceName());
    }

    /** @param \ReflectionClass<object> $reflectionClass */
    public static function getValuesFromMethod(
        \ReflectionClass $reflectionClass,
        string $method,
    ): NamespaceVisibilitySetting {
        $values = AttributeValueReader::getAttributeValuesForMethod(
            $reflectionClass,
            $method,
            NamespaceVisibility::class,
        );

        return self::parseValues($values, $reflectionClass->getNamespaceName());
    }

    /** @param array<array-key, mixed>|null $values */
    private static function parseValues(?array $values, string $attributesNamespace): NamespaceVisibilitySetting
    {
        if (null === $values) {
            return NamespaceVisibilitySetting::noNamespaceVisibilityAttribute();
        }

        $namespace = $values['namespace'] ?? $values[0] ?? $attributesNamespace;
        \assert(is_string($namespace));

        $excludeSubNamespaces = $values['excludeSubNamespaces'] ?? $values[1] ?? false;
        \assert(is_bool($excludeSubNamespaces));

        return NamespaceVisibilitySetting::namespaceVisibility($namespace, $excludeSubNamespaces);
    }
}

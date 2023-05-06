<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\AttributeValueReaders;

class AttributeValueReader
{
    /**
     * @param \ReflectionAttribute<object> $attribute
     *
     * @return list<string>
     */
    public static function getStrings(\ReflectionAttribute $attribute): array
    {
        $values = [];
        foreach ($attribute->getArguments() as $value) {
            if (is_string($value)) {
                $values[] = $value;
            }
        }

        return $values;
    }

    /**
     * @param \ReflectionAttribute<object> $attribute
     */
    public static function getString(\ReflectionAttribute $attribute, int $position, string $parameterName): ?string
    {
        $arguments = $attribute->getArguments();

        if (array_key_exists($position, $arguments)) {
            $value = $arguments[$position];
        } elseif (array_key_exists($parameterName, $arguments)) {
            $value = $arguments[$parameterName];
        } else {
            return null;
        }

        if (!is_string($value)) {
            return null;
        }

        return $value;
    }

    /**
     * @param \ReflectionAttribute<object> $attribute
     */
    public static function getBool(\ReflectionAttribute $attribute, int $position, string $parameterName): ?bool
    {
        $arguments = $attribute->getArguments();

        if (array_key_exists($position, $arguments)) {
            $value = $arguments[$position];
        } elseif (array_key_exists($parameterName, $arguments)) {
            $value = $arguments[$parameterName];
        } else {
            return null;
        }

        if (!is_bool($value)) {
            return null;
        }

        return $value;
    }
}

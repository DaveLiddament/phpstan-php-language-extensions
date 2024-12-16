<?php

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Helpers;

final class Assert
{
    /** @phpstan-assert array<mixed> $value */
    public static function assertArray(mixed $value): void
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException('Expecting value to be an array');
        }
    }

    /** @phpstan-assert string $value */
    public static function assertString(mixed $value): void
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException('Expecting value to be a string');
        }
    }

    /** @phpstan-assert int $value */
    public static function assertInt(mixed $value): void
    {
        if (!is_int($value)) {
            throw new \InvalidArgumentException('Expecting value to be an int');
        }
    }

    /**
     * @phpstan-assert array<int,string> $values
     */
    public static function arrayOfStrings(mixed $values): void
    {
        self::assertArray($values);
        foreach ($values as $key => $value) {
            self::assertInt($key);
            self::assertString($value);
        }
    }
}

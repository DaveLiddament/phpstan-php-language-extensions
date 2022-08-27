<?php

declare(strict_types=1);


namespace DaveLiddament\PhpstanPhpLanguageExtensions\Helpers;


class SubNamespaceChecker
{

    public static function isSubNamespace(string $namespace, string $namespaceToCheckAgainst): bool
    {
        if ($namespace === $namespaceToCheckAgainst) {
            return true;
        }

        if (str_starts_with(needle: $namespaceToCheckAgainst . '\\', haystack: $namespace )) {
            return true;
        }

        return false;
    }
}

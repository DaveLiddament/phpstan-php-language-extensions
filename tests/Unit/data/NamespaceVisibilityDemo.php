<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data;

use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;

class NamespaceVisibilityDemo
{
    #[NamespaceVisibility('Acme', true)]
    public function bothByPosition(): void
    {
    }

    #[NamespaceVisibility(namespace: 'Acme', excludeSubNamespaces: true)]
    public function bothByName(): void
    {
    }

    #[NamespaceVisibility('Acme', excludeSubNamespaces: true)]
    public function mix(): void
    {
    }

    #[NamespaceVisibility(namespace: 'Acme')]
    public function justNamespaceByName(): void
    {
    }

    #[NamespaceVisibility('Acme')]
    public function justNamespaceByPosition(): void
    {
    }

    #[NamespaceVisibility(excludeSubNamespaces: true)]
    public function justExcludeByName(): void
    {
    }
}

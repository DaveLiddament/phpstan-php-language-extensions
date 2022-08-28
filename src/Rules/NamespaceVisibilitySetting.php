<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

use LogicException;

class NamespaceVisibilitySetting
{
    public static function noNamespaceVisibilityAttribute(): self
    {
        return new self(false, null, null);
    }

    public static function namespaceVisibility(string $namespace, bool $excludeSubNamespaces): self
    {
        return new self(true, $namespace, $excludeSubNamespaces);
    }

    private function __construct(
        private bool $hasNamespace,
        private ?string $namespace,
        private ?bool $excludeSubNamespace
    ) {
    }

    public function hasNamespaceAttribute(): bool
    {
        return $this->hasNamespace;
    }

    public function getNamespace(): string
    {
        if (null === $this->namespace) {
            throw new LogicException('Only call getNamespace if hasNamespaceAttribute returns true');
        }

        return $this->namespace;
    }

    public function isExcludeSubNamespaces(): bool
    {
        if (null === $this->excludeSubNamespace) {
            throw new LogicException('Only call isExcludeSubNamespace if hasNamespaceAttribute returns true');
        }

        return $this->excludeSubNamespace;
    }
}

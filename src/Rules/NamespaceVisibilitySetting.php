<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Rules;

/**
 * Holds cached values of NamespaceVisibility settings for class and methods.
 */
class NamespaceVisibilitySetting
{
    public static function noNamespaceVisibilityAttribute(): self
    {
        return new self(false, null, null);
    }

    public static function namespaceVisibility(?string $namespace, bool $excludeSubNamespaces): self
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

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function isExcludeSubNamespaces(): bool
    {
        if (null === $this->excludeSubNamespace) {
            throw new \LogicException('Only call isExcludeSubNamespace if hasNamespaceAttribute returns true');
        }

        return $this->excludeSubNamespace;
    }
}

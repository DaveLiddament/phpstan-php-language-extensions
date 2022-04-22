<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Config;

class TestConfig
{
    public const NONE = 'none';
    public const CLASS_NAME = 'className';
    public const NAMESPACE = 'namespace';

    public const VALID_NODES = [
        self::NONE,
        self::CLASS_NAME,
        self::NAMESPACE,
    ];

    private string $mode;
    private ?string $testNamespace;

    public function __construct(
        string $mode,
        ?string $testNamespace = null,
    ) {
        if (!in_array(
            needle: $mode,
            haystack: self::VALID_NODES,
            strict: true,
        )) {
            throw new \InvalidArgumentException("Invalid mode [$mode]. Use one of:".implode('|', self::VALID_NODES));
        }

        if (self::NAMESPACE === $mode) {
            if (null === $testNamespace) {
                throw new \InvalidArgumentException("Using mode [$mode] requires testNamespace to be set.");
            }
        } elseif (null !== $testNamespace) {
            throw new \InvalidArgumentException("TestNamespace has been set, but you're not using mode: ".self::NAMESPACE);
        }

        $this->mode = $mode;
        $this->testNamespace = $testNamespace;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function getTestNamespace(): string
    {
        if (null === $this->testNamespace) {
            throw new \LogicException('Attempting to get testNamespace when it is not set.');
        }

        return $this->testNamespace;
    }
}

<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Helpers;

use function array_key_exists;
use Closure;

/**
 * @template TValue
 */
class Cache
{
    /** @var array<string,TValue> */
    private array $cache = [];

    public function hasEntry(string $key): bool
    {
        return array_key_exists($key, $this->cache);
    }

    /** @return TValue */
    public function getEntry(string $key)
    {
        if (!array_key_exists($key, $this->cache)) {
            throw new \LogicException('Call hasEntry first');
        }

        return $this->cache[$key];
    }

    /** @param TValue $entry */
    public function addEntry(string $key, $entry): void
    {
        $this->cache[$key] = $entry;
    }

    /**
     * @param Closure():TValue $initializer
     *
     * @return TValue
     */
    public function get(string $key, Closure $initializer): mixed
    {
        if (!array_key_exists($key, $this->cache)) {
            $this->cache[$key] = $initializer();
        }

        return $this->cache[$key];
    }
}

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

    /**
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

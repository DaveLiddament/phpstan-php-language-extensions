<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit;

use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\Cache;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    private const ENTRY_1 = 'entry 1';
    private const VALUE_1 = 'value 1';

    /**
     * @var Cache<string>
     */
    private Cache $cache;

    protected function setup(): void
    {
        $this->cache = new Cache();
    }

    public function testEmptyCache(): void
    {
        $this->assertFalse($this->cache->hasEntry(self::ENTRY_1));
    }

    public function testAddValueToCache(): void
    {
        $this->cache->addEntry(self::ENTRY_1, self::VALUE_1);
        $this->assertTrue($this->cache->hasEntry(self::ENTRY_1));
        $this->assertSame(self::VALUE_1, $this->cache->getEntry(self::ENTRY_1));
    }

    public function testAccessMissingEntry(): void
    {
        $this->expectException(\LogicException::class);
        $this->cache->getEntry(self::ENTRY_1);
    }
}

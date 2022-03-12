<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use PHPUnit\Framework\TestCase;

class TestConfigTest extends TestCase
{
    private const NAMESPACE = '\\foo\\bar';

    public function testModeNode(): void
    {
        $testConfig = new TestConfig(TestConfig::NONE);
        $this->assertSame(TestConfig::NONE, $testConfig->getMode());
    }

    public function testDefault(): void
    {
        $testConfig = new TestConfig();
        $this->assertSame(TestConfig::NONE, $testConfig->getMode());
    }

    public function testModeClassName(): void
    {
        $testConfig = new TestConfig(TestConfig::CLASS_NAME);
        $this->assertSame(TestConfig::CLASS_NAME, $testConfig->getMode());
    }

    public function testNamespace(): void
    {
        $testConfig = new TestConfig(TestConfig::NAMESPACE, self::NAMESPACE);
        $this->assertSame(TestConfig::NAMESPACE, $testConfig->getMode());
        $this->assertSame(self::NAMESPACE, $testConfig->getTestNamespace());
    }

    public function testNamespaceMustBeSuppliedInNamespaceMode(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new TestConfig(TestConfig::NAMESPACE);
    }

    public function testErrorIfNamespaceSuppliedWhenNotNamespaceMode(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new TestConfig(TestConfig::NONE, self::NAMESPACE);
    }
}

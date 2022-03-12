<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\TestClassChecker;
use PHPUnit\Framework\TestCase;

class TestClassCheckerTest extends TestCase
{
    private const TEST_NAMESPACE = '\\Test\\Project\\';
    private const TEST_SUB_NAMESPACE = '\\Test\\Project\\Bar';
    private const TEST_CLASS_NAME = 'FooTest';

    private const NON_TEST_NAMESPACE = '\\Project\\';
    private const NON_TEST_CLASS_NAME = 'Foo';

    public function testModeNone(): void
    {
        $testConfig = new TestConfig(TestConfig::NONE);
        $testClassChecker = new TestClassChecker($testConfig);
        $this->assertFalse($testClassChecker->isTestClass(self::TEST_NAMESPACE, self::TEST_CLASS_NAME));
    }

    public function testModeClassNameOnTestClass(): void
    {
        $testConfig = new TestConfig(TestConfig::CLASS_NAME);
        $testClassChecker = new TestClassChecker($testConfig);
        $this->assertTrue($testClassChecker->isTestClass(self::TEST_NAMESPACE, self::TEST_CLASS_NAME));
    }

    public function testModeClassNameOnNonTestClass(): void
    {
        $testConfig = new TestConfig(TestConfig::CLASS_NAME);
        $testClassChecker = new TestClassChecker($testConfig);
        $this->assertFalse($testClassChecker->isTestClass(self::TEST_NAMESPACE, self::NON_TEST_CLASS_NAME));
    }

    public function testModeNamespaceInTestNamespace(): void
    {
        $testConfig = new TestConfig(TestConfig::NAMESPACE, self::TEST_NAMESPACE);
        $testClassChecker = new TestClassChecker($testConfig);
        $this->assertTrue($testClassChecker->isTestClass(self::TEST_NAMESPACE, self::NON_TEST_CLASS_NAME));
    }

    public function testModeNamespaceInTestSubNamespace(): void
    {
        $testConfig = new TestConfig(TestConfig::NAMESPACE, self::TEST_NAMESPACE);
        $testClassChecker = new TestClassChecker($testConfig);
        $this->assertTrue($testClassChecker->isTestClass(self::TEST_SUB_NAMESPACE, self::NON_TEST_CLASS_NAME));
    }

    public function testModeNamespaceNotInTestNamespace(): void
    {
        $testConfig = new TestConfig(TestConfig::NAMESPACE, self::TEST_NAMESPACE);
        $testClassChecker = new TestClassChecker($testConfig);
        $this->assertFalse($testClassChecker->isTestClass(self::NON_TEST_NAMESPACE, self::NON_TEST_CLASS_NAME));
    }
}

<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit;

use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\NamespaceVisibilitySettingsParser;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\NamespaceVisibilityBothArguments;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\NamespaceVisibilityBothNamedArguments;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\NamespaceVisibilityFirstArgument;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\NamespaceVisibilityNamedExcludeSubNamespacesArgument;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\NamespaceVisibilityNamedNamespaceArgument;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\NamespaceVisibilityNoArguments;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\NoNamespaceVisibilityAttribute;
use PHPUnit\Framework\TestCase;

class NamespaceVisibilityOptionsParserTest extends TestCase
{
    private const OVERRIDDEN_VALUE = 'foo/bar';
    private const NON_OVERRIDDEN_NAMESPACE = 'DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data';

    /** @return array<array-key, array{class-string, ?string, bool}> */
    public function dataProvider(): array
    {
        return [
            [
                NamespaceVisibilityBothArguments::class,
                self::OVERRIDDEN_VALUE,
                true,
            ],
            [
                NamespaceVisibilityBothNamedArguments::class,
                self::OVERRIDDEN_VALUE,
                true,
            ],
            [
                NamespaceVisibilityFirstArgument::class,
                self::OVERRIDDEN_VALUE,
                false,
            ],
            [
                NamespaceVisibilityNamedExcludeSubNamespacesArgument::class,
                self::NON_OVERRIDDEN_NAMESPACE,
                true,
            ],
            [
                NamespaceVisibilityNamedNamespaceArgument::class,
                self::OVERRIDDEN_VALUE,
                false,
            ],
            [
                NamespaceVisibilityNoArguments::class,
                self::NON_OVERRIDDEN_NAMESPACE,
                false,
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     *
     * @param class-string $class
     */
    public function testParsingNamespaceVisibilityOptions(
        string $class,
        ?string $expectedNamespace,
        bool $expectedExcludeSubNamespaces,
    ): void {
        $reflectionClass = new \ReflectionClass($class);
        $namespaceVisibilitySettings = NamespaceVisibilitySettingsParser::getValuesFromClass($reflectionClass);
        $this->assertTrue($namespaceVisibilitySettings->hasNamespaceAttribute());
        $this->assertSame($expectedNamespace, $namespaceVisibilitySettings->getNamespace());
        $this->assertSame($expectedExcludeSubNamespaces, $namespaceVisibilitySettings->isExcludeSubNamespaces());
    }

    public function testNoNamespaceVisibilityAttribute(): void
    {
        $reflectionClass = new \ReflectionClass(NoNamespaceVisibilityAttribute::class);
        $namespaceVisibilitySettings = NamespaceVisibilitySettingsParser::getValuesFromClass($reflectionClass);
        $this->assertFalse($namespaceVisibilitySettings->hasNamespaceAttribute());
    }
}

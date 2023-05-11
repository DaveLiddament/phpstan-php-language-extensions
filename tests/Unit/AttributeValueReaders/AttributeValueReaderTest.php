<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\AttributeValueReaders;

use DaveLiddament\PhpLanguageExtensions\Friend;
use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;
use DaveLiddament\PhpstanPhpLanguageExtensions\AttributeValueReaders\AttributeFinder;
use DaveLiddament\PhpstanPhpLanguageExtensions\AttributeValueReaders\AttributeValueReader;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\Bar;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\Class1Friend;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\Class2Friends;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\Foo;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\NamespaceVisibilityDemo;
use PHPUnit\Framework\TestCase;

class AttributeValueReaderTest extends TestCase
{
    /** @return list<array{class-string, list<class-string>}> */
    public function getStringsDataProvider(): array
    {
        return [
            [
                Class1Friend::class,
                [
                    Foo::class,
                ],
            ],
            [
                Class2Friends::class,
                [
                    Foo::class,
                    Bar::class,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getStringsDataProvider
     *
     * @param class-string $className
     * @param list<class-string> $expectedStrings
     */
    public function testGetStrings(string $className, array $expectedStrings): void
    {
        $attribute = AttributeFinder::getAttributeOnClass(
            new \ReflectionClass(new $className()),
            Friend::class,
        );

        $this->assertNotNull($attribute);
        $actual = AttributeValueReader::getStrings($attribute);
        $this->assertSame($expectedStrings, $actual);
    }

    /** @return list<array{string, ?string, ?bool}> */
    public function namespaceVisibilityDataProvider(): array
    {
        return [
            [
                'bothByPosition',
                'Acme',
                true,
            ],
            [
                'bothByName',
                'Acme',
                true,
            ],
            [
                'mix',
                'Acme',
                true,
            ],
            [
                'justNamespaceByName',
                'Acme',
                null,
            ],
            [
                'justNamespaceByPosition',
                'Acme',
                null,
            ],
            [
                'justExcludeByName',
                null,
                true,
            ],
        ];
    }

    /** @dataProvider namespaceVisibilityDataProvider */
    public function testNamespaceAttributeValuesTest(string $methodName, ?string $namespace, ?bool $exclude): void
    {
        $attribute = AttributeFinder::getAttributeOnMethod(
            new \ReflectionClass(new NamespaceVisibilityDemo()),
            $methodName,
            NamespaceVisibility::class,
        );

        $this->assertNotNull($attribute);
        $this->assertSame($namespace, AttributeValueReader::getString($attribute, 0, 'namespace'));
        $this->assertSame($exclude, AttributeValueReader::getBool($attribute, 1, 'excludeSubNamespaces'));
    }
}

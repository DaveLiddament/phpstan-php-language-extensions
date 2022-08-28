<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit;

use DaveLiddament\PhpLanguageExtensions\Friend;
use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\AttributeValueReader;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\Bar;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\Class0Friends;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\Class1Friend;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\Class2Friends;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\Foo;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\MethodAttributes;
use PHPUnit\Framework\TestCase;

class AttributeValueReaderTest extends TestCase
{
    /** @return array<int,array{string, array<int,class-string>|null}> */
    public function methodDataProvider(): array
    {
        return [
            ['noAttribute', null],
            ['__construct', null],
            ['friendWithOneValue', [Foo::class]],
            ['friendWithTwoValues', [Foo::class, Bar::class]],
            ['friendWithOneValueInArray', [Bar::class]],
        ];
    }

    /**
     * @dataProvider methodDataProvider
     *
     * @param array<int,class-string> $expectedValues
     */
    public function testGettingMethodAttributeValues(string $methodName, ?array $expectedValues): void
    {
        $actualValues = AttributeValueReader::getAttributeValuesForMethod(
            new \ReflectionClass(MethodAttributes::class),
            $methodName,
            Friend::class,
        );

        $this->assertEquals($expectedValues, $actualValues);
    }

    /** @return array<int,array{class-string, array<int,class-string>|null}> */
    public function classDataProvider(): array
    {
        return [
            [Class0Friends::class, null],
            [Class1Friend::class, [Foo::class]],
            [Class2Friends::class, [Foo::class, Bar::class]],
        ];
    }

    /**
     * @dataProvider classDataProvider
     *
     * @param class-string $class
     * @param array<int,class-string>|null $expectedValues
     */
    public function testGettingClassAttributeValues(string $class, ?array $expectedValues): void
    {
        $actualValues = AttributeValueReader::getAttributeValuesForClass(
            new \ReflectionClass($class),
            Friend::class,
        );

        $this->assertEquals($expectedValues, $actualValues);
    }
}

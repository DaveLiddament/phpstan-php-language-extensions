<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\AttributeValueReaders;

use DaveLiddament\PhpLanguageExtensions\Friend;
use DaveLiddament\PhpstanPhpLanguageExtensions\AttributeValueReaders\AttributeFinder;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\Class0Friends;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\Class1Friend;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\Foo;
use DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data\MethodAttributes;
use PHPUnit\Framework\TestCase;

class AttributeFinderTest extends TestCase
{
    public function testFindAttributeOnClass(): void
    {
        $attribute = AttributeFinder::getAttributeOnClass(
            new \ReflectionClass(Class1Friend::class),
            Friend::class,
        );

        $this->assertNotNull($attribute);
        $this->assertSame($attribute->getName(), Friend::class);
        $this->assertSame([Foo::class], $attribute->getArguments());
    }

    public function testFindAttributeNotFoundOnClass(): void
    {
        $attribute = AttributeFinder::getAttributeOnClass(
            new \ReflectionClass(Class0Friends::class),
            Friend::class,
        );

        $this->assertNull($attribute);
    }

    public function testFindAttributeOnMethod(): void
    {
        $attribute = AttributeFinder::getAttributeOnMethod(
            new \ReflectionClass(MethodAttributes::class),
            'friendWithOneValue',
            Friend::class,
        );

        $this->assertNotNull($attribute);
        $this->assertSame($attribute->getName(), Friend::class);
    }

    public function testFindAttributeNotOnMethod(): void
    {
        $attribute = AttributeFinder::getAttributeOnMethod(
            new \ReflectionClass(MethodAttributes::class),
            'noAttribute',
            Friend::class,
        );

        $this->assertNull($attribute);
    }

    public function testHasAttributeOnClass(): void
    {
        $this->assertTrue(AttributeFinder::hasAttributeOnClass(
            new \ReflectionClass(Class1Friend::class),
            Friend::class,
        ));
    }

    public function testHasAttributeNotFoundOnClass(): void
    {
        $this->assertFalse(AttributeFinder::hasAttributeOnClass(
            new \ReflectionClass(Class0Friends::class),
            Friend::class,
        ));
    }

    public function testHasAttributeOnMethod(): void
    {
        $this->assertTrue(AttributeFinder::hasAttributeOnMethod(
            new \ReflectionClass(MethodAttributes::class),
            'friendWithOneValue',
            Friend::class,
        ));
    }

    public function testHasAttributeNotOnMethod(): void
    {
        $this->assertFalse(AttributeFinder::hasAttributeOnMethod(
            new \ReflectionClass(MethodAttributes::class),
            'noAttribute',
            Friend::class,
        ));
    }
}

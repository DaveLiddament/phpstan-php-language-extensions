<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit;

use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\SubNamespaceChecker;
use PHPUnit\Framework\TestCase;

class SubNamespaceCheckerTest extends TestCase
{
    /** @return array<array-key, array{bool, string, string}> */
    public function dataProvider(): array
    {
        return [
            'same namespace' => [
                true,
                'Foo\Bar',
                'Foo\Bar',
            ],
            'different namespace' => [
                false,
                'Foo\Bar',
                'Foo\Baz',
            ],
            'sub namespace' => [
                true,
                'Foo\Bar\Baz',
                'Foo\Bar',
            ],
            'not sub namespace 1' => [
                false,
                'Foo\Bart',
                'Foo\Bar',
            ],
        ];
    }

    /** @dataProvider dataProvider */
    public function testSubNamespaceChecker(bool $expected, string $namespace, string $namespaceToCheckAgainst): void
    {
        $actual = SubNamespaceChecker::isSubNamespace($namespace, $namespaceToCheckAgainst);
        $this->assertSame($expected, $actual);
    }
}

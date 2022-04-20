<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data;

use DaveLiddament\PhpLanguageExtensions\Friend;

class MethodAttributes
{
    public function noAttribute(): void
    {
    }

    #[Friend(Foo::class)]
    public function friendWithOneValue(): void
    {
    }

    #[Friend(Foo::class, Bar::class)]
    public function friendWithTwoValues(): void
    {
    }

    #[Friend(Bar::class)]
    public function friendWithOneValueInArray(): void
    {
    }
}

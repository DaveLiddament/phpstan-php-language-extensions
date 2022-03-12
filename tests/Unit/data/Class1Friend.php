<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data;

use DaveLiddament\PhpstanPhpLanguageExtensions\Attributes\Friend;

#[Friend(Foo::class)]
class Class1Friend
{
}

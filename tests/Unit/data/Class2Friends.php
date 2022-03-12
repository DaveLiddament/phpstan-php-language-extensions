<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data;

use DaveLiddament\PhpstanPhpLanguageExtensions\Attributes\Friend;

#[Friend([Foo::class, Bar::class])]
class Class2Friends
{
}

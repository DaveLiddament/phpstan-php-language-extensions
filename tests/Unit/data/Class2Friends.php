<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data;

use DaveLiddament\PhpLanguageExtensions\Friend;

#[Friend([Foo::class, Bar::class])]
class Class2Friends
{
}

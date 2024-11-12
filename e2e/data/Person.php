<?php

namespace data;

use DaveLiddament\PhpLanguageExtensions\Friend;

#[Friend(PersonBuilder::class)]
class Person
{
    public function __construct()
    {
    }

    public function aMethod(): void
    {
    }

    public static function aStaticMethod(): void
    {
    }
}

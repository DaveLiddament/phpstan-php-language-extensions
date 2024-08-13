<?php

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Build\e2e\data;

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

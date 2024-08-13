<?php

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Build\e2e\data;

class PersonBuilder
{
    public function create(): Person
    {
        return new Person();
    }
}

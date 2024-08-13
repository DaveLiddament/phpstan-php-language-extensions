<?php

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Build\e2e\data;

class FriendProblems
{
    public function badCode(Person $person): void
    {
        new Person();
        Person::aStaticMethod();
        $person->aMethod();
    }
}

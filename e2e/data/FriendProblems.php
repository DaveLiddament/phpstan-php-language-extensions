<?php

namespace data;

class FriendProblems
{
    public function badCode(Person $person): void
    {
        new Person(); /** @phpstan-ignore new.resultUnused */
        Person::aStaticMethod();
        $person->aMethod();
    }
}

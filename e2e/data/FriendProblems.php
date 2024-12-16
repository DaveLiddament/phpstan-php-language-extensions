<?php

namespace data;

class FriendProblems
{
    public function badCode(Person $person): void
    {
        $person = new Person();
        Person::aStaticMethod();
        $person->aMethod();
    }
}

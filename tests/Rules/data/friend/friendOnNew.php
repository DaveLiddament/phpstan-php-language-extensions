<?php

namespace FriendOnNew;

use DaveLiddament\PhpLanguageExtensions\Friend;

#[Friend(PersonBuilder::class)]
class Person
{
    public static function create(): Person
    {
        return new Person(); // OK
    }
}

class Exam
{
    public function addPerson(): void
    {
        new Person(); // ERROR
    }
}

new Person(); // ERROR

class PersonBuilder
{
    public function build(): Person
    {
        return new Person(); // OK
    }
}

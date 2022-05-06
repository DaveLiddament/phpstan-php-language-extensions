<?php

namespace FriendOnConstructor;

use DaveLiddament\PhpLanguageExtensions\Friend;

class Person
{
    #[Friend(PersonBuilder::class)]
    public function __construct()
    {
    }

    public static function create(): Person
    {
        return new Person(); // OK
    }

    public static function createSelf(): self
    {
        return new self(); // OK
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

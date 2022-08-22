<?php

namespace CallableFromOnNew;

use DaveLiddament\PhpLanguageExtensions\CallableFrom;

#[CallableFrom(PersonBuilder::class)]
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

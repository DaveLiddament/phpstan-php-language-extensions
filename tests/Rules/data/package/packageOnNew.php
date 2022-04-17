<?php

namespace PackageOnNew {

    use DaveLiddament\PhpLanguageExtensions\Package;

    #[Package]
    class Person
    {
        public static function create(): Person
        {
            return new Person(); // OK
        }
    }

    class PersonBuilder
    {
        public function build(): Person
        {
            return new Person(); // OK
        }
    }

    new Person(); // OK
}

namespace PackageOnNew2 {

    use PackageOnNew\Person;

    class Exam
    {
        public function addPerson(): void
        {
            new Person(); // ERROR
        }
    }
}

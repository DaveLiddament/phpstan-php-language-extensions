<?php

namespace NamespaceVisibilityOnNew {

    use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;

    #[NamespaceVisibility]
    class Person
    {
        public static function create(): Person
        {
            return new Person(); // OK
        }

        public static function createSelf(): self
        {
            return new self(); // OK
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

namespace NamespaceVisibilityOnNew\SubNamespace {

    use NamespaceVisibilityOnNew\Person;

    class AnotherPersonBuilder
    {
        public function build(): Person
        {
            return new Person(); // OK - Subnamespace of NamespaceVisibilityOnMethod, which is allowed
        }
    }
}


namespace NamespaceVisibilityOnNew2 {

    use NamespaceVisibilityOnNew\Person;

    class Exam
    {
        public function addPerson(): void
        {
            new Person(); // ERROR
        }
    }
}

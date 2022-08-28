<?php

namespace NamespaceVisibilityOnNewExcludeSubNamespaces {

    use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;

    #[NamespaceVisibility(excludeSubNamespaces: true)]
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

namespace NamespaceVisibilityOnNewExcludeSubNamespaces\SubNamespace {

    use NamespaceVisibilityOnNewExcludeSubNamespaces\Person;

    class AnotherPersonBuilder
    {
        public function build(): Person
        {
            return new Person(); // ERROR - Subnamespace of NamespaceVisibilityOnMethod not allowed
        }
    }
}

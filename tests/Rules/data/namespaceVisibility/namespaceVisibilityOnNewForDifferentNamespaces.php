<?php

namespace NamespaceVisibilityOnNewForDifferentNamespaces {

    use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;

    class Person
    {
        #[NamespaceVisibility(namespace: 'NamespaceVisibilityOnNewDifferentNamespace')]
        public function __construct()
        {
        }

    }
}


namespace NamespaceVisibilityOnNewDifferentNamespace {

    use NamespaceVisibilityOnNewForDifferentNamespaces\Person;

    class AnotherPersonBuilder
    {
        public function create(): void
        {
            new Person(); // OK
        }
    }
}

namespace NamespaceVisibilityOnNewDifferentNamespace\SubNamespace {

    use NamespaceVisibilityOnNewForDifferentNamespaces\Person;

    class AnotherPersonBuilder
    {
        public function create(): void
        {
            new Person(); // OK
        }
    }
}

namespace NamespaceVisibilityOnNewDifferentNamespace2 {

    use NamespaceVisibilityOnNewForDifferentNamespaces\Person;

    class AnotherPersonBuilder
    {
        public function create(): void
        {
            new Person(); // ERROR
        }
    }
}

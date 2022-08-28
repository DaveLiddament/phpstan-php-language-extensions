<?php

namespace NamespaceVisibilityOnNewForDifferentNamespaces {

    use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;

    #[NamespaceVisibility(namespace: 'NamespaceVisibilityOnNewForDifferentNamespaces2')]
    class Person
    {
    }
}



namespace NamespaceVisibilityOnNewForDifferentNamespaces2 {

    use NamespaceVisibilityOnNewForDifferentNamespaces\Person;

    class Exam
    {
        public function addPerson(): void
        {
            new Person(); // OK
        }
    }
}

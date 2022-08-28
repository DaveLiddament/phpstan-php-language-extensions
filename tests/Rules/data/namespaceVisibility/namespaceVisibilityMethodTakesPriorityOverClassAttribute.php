<?php

declare(strict_types=1);

namespace NamespaceVisibilityMethodTakesPriorityOverClassAttribute {

    use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;

    #[NamespaceVisibility(namespace: 'NamespaceVisibilityMethodTakesPriorityOverClassAttribute\ClassNamespace')]
    class Person
    {
        #[NamespaceVisibility(namespace: 'NamespaceVisibilityMethodTakesPriorityOverClassAttribute\MethodNamespace')]
        public function updateName(): void
        {
        }
    }

}

namespace NamespaceVisibilityMethodTakesPriorityOverClassAttribute\ClassNamespace {

    use NamespaceVisibilityMethodTakesPriorityOverClassAttribute\Person;

    class ClassPersonUpdater
    {
        public function update(Person $person): void
        {
            $person->updateName(); // ERROR - Method takes priority over Class attributes
        }
    }
}

namespace NamespaceVisibilityMethodTakesPriorityOverClassAttribute\MethodNamespace {

    use NamespaceVisibilityMethodTakesPriorityOverClassAttribute\Person;

    class MethodPersonUpdater
    {
        public function update(Person $person): void
        {
            $person->updateName(); // OK
        }
    }
}
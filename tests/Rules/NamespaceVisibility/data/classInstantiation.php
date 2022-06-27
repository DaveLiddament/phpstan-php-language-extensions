<?php

declare(strict_types=1);

namespace {
    use ClassInstantiation\PrivateClass;
    use ClassInstantiation\ProtectedClass;
    use ClassInstantiation\PublicClass;
    use ClassInstantiation\PublicClassWithAttribute;
    use DaveLiddament\PhpLanguageExtensions\AccessModifier;
    use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;

    class GlobalPublicClass
    {
    }

    #[NamespaceVisibility(AccessModifier::Public)]
    class GlobalPublicClassWithAttribute
    {
    }

    #[NamespaceVisibility(AccessModifier::Protected)]
    class GlobalProtectedClass
    {
    }

    #[NamespaceVisibility(AccessModifier::Private)]
    class GlobalPrivateClass
    {
    }

    $public = new PublicClass(); // OK
    $public1 = new PublicClassWithAttribute(); // OK
    $protected = new ProtectedClass(); // ERROR
    $private = new PrivateClass(); // ERROR

    $public = new GlobalPublicClass(); // OK
    $public2 = new GlobalPublicClassWithAttribute(); // OK
    $protected = new GlobalProtectedClass(); // OK
    $private = new GlobalPrivateClass(); // OK
}

namespace ClassInstantiation {
    use DaveLiddament\PhpLanguageExtensions\AccessModifier;
    use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;
    use GlobalPrivateClass;
    use GlobalProtectedClass;
    use GlobalPublicClass;
    use GlobalPublicClassWithAttribute;

    class PublicClass
    {
    }

    #[NamespaceVisibility(AccessModifier::Public)]
    class PublicClassWithAttribute
    {
    }

    #[NamespaceVisibility(AccessModifier::Protected)]
    class ProtectedClass
    {
    }

    #[NamespaceVisibility(AccessModifier::Private)]
    class PrivateClass
    {
    }

    $public = new PublicClass(); // OK
    $public2 = new PublicClassWithAttribute(); // OK
    $protected = new ProtectedClass(); // OK
    $private = new PrivateClass(); // OK

    $public = new GlobalPublicClass(); // OK
    $public2 = new GlobalPublicClassWithAttribute(); // OK
    $protected = new GlobalProtectedClass(); // OK
    $private = new GlobalPrivateClass(); // ERROR
}

namespace ClassInstantiation\Nested {
    use ClassInstantiation\PrivateClass;
    use ClassInstantiation\ProtectedClass;
    use ClassInstantiation\PublicClass;
    use ClassInstantiation\PublicClassWithAttribute;
    use GlobalPrivateClass;
    use GlobalProtectedClass;
    use GlobalPublicClass;
    use GlobalPublicClassWithAttribute;

    class Factory
    {
        public function testing(): void
        {
            $public = new PublicClass(); // OK
            $public2 = new PublicClassWithAttribute(); // OK
            $protected = new ProtectedClass(); // OK
            $private = new PrivateClass(); // ERROR

            $public = new GlobalPublicClass(); // OK
            $public2 = new GlobalPublicClassWithAttribute(); // OK
            $protected = new GlobalProtectedClass(); // OK
            $private = new GlobalPrivateClass(); // ERROR
        }
    }

    $public = new PublicClass(); // OK
    $public2 = new PublicClassWithAttribute(); // OK
    $protected = new ProtectedClass(); // OK
    $private = new PrivateClass(); // ERROR

    $public = new GlobalPublicClass(); // OK
    $public2 = new GlobalPublicClassWithAttribute(); // OK
    $protected = new GlobalProtectedClass(); // OK
    $private = new GlobalPrivateClass(); // ERROR
}

namespace ClassInstantiationInOtherVendor {
    use ClassInstantiation\PrivateClass;
    use ClassInstantiation\ProtectedClass;
    use ClassInstantiation\PublicClass;
    use ClassInstantiation\PublicClassWithAttribute;
    use GlobalPrivateClass;
    use GlobalProtectedClass;
    use GlobalPublicClass;
    use GlobalPublicClassWithAttribute;

    class Factory
    {
        public function testing(): void
        {
            $public = new PublicClass(); // OK
            $public2 = new PublicClassWithAttribute(); // OK
            $protected = new ProtectedClass(); // ERROR
            $private = new PrivateClass(); // ERROR

            $public = new GlobalPublicClass(); // OK
            $public2 = new GlobalPublicClassWithAttribute(); // OK
            $protected = new GlobalProtectedClass(); // OK
            $private = new GlobalPrivateClass(); // ERROR
        }
    }

    $public = new PublicClass(); // OK
    $public2 = new PublicClassWithAttribute(); // OK
    $protected = new ProtectedClass(); // ERROR
    $private = new PrivateClass(); // ERROR

    $public = new GlobalPublicClass(); // OK
    $public2 = new GlobalPublicClassWithAttribute(); // OK
    $protected = new GlobalProtectedClass(); // OK
    $private = new GlobalPrivateClass(); // ERROR
}

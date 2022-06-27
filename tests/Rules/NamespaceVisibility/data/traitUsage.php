<?php

declare(strict_types=1);

namespace {
    use DaveLiddament\PhpLanguageExtensions\AccessModifier;
    use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;
    use traitUsage\PrivateTrait;
    use traitUsage\ProtectedTrait;
    use traitUsage\PublicTrait;
    use traitUsage\PublicTraitWithAttribute;

    trait GlobalPublic
    {
    }

    #[NamespaceVisibility(AccessModifier::Public)]
    trait GlobalPublicWithAttribute
    {
    }

    #[NamespaceVisibility(AccessModifier::Protected)]
    trait GlobalProtected
    {
    }

    #[NamespaceVisibility(AccessModifier::Private)]
    trait GlobalPrivate
    {
    }

    final class ExampleUsage
    {
        use GlobalPrivate; // OK
        use GlobalProtected; // OK
        use GlobalPublic; // OK
        use GlobalPublicWithAttribute; // OK

        use PrivateTrait; // ERROR
        use ProtectedTrait; // ERROR
        use PublicTrait; // OK
        use PublicTraitWithAttribute; // OK
    }
}

namespace traitUsage {
    use DaveLiddament\PhpLanguageExtensions\AccessModifier;
    use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;
    use GlobalPrivate;
    use GlobalProtected;
    use GlobalPublic;
    use GlobalPublicWithAttribute;

    trait PublicTrait
    {
    }

    #[NamespaceVisibility(AccessModifier::Public)]
    trait PublicTraitWithAttribute
    {
    }

    #[NamespaceVisibility(AccessModifier::Protected)]
    trait ProtectedTrait
    {
    }

    #[NamespaceVisibility(AccessModifier::Private)]
    trait PrivateTrait
    {
    }

    final class ExampleUsage
    {
        use GlobalPrivate; // ERROR
        use GlobalProtected; // OK
        use GlobalPublic; // OK
        use GlobalPublicWithAttribute; // OK

        use PrivateTrait; // OK
        use ProtectedTrait; // OK
        use PublicTrait; // OK
        use PublicTraitWithAttribute; // OK
    }
}

namespace traitUsage\Nested {
    use GlobalPrivate;
    use GlobalProtected;
    use GlobalPublic;
    use GlobalPublicWithAttribute;
    use traitUsage\PrivateTrait;
    use traitUsage\ProtectedTrait;
    use traitUsage\PublicTrait;
    use traitUsage\PublicTraitWithAttribute;

    final class ExampleUsage
    {
        use GlobalPrivate; // ERROR
        use GlobalProtected; // OK
        use GlobalPublic; // OK
        use GlobalPublicWithAttribute; // OK

        use PrivateTrait; // ERROR
        use ProtectedTrait; // OK
        use PublicTrait; // OK
        use PublicTraitWithAttribute; // OK
    }
}

namespace traitUsageInOtherVendor {
    use GlobalPrivate;
    use GlobalProtected;
    use GlobalPublic;
    use GlobalPublicWithAttribute;
    use traitUsage\PrivateTrait;
    use traitUsage\ProtectedTrait;
    use traitUsage\PublicTrait;
    use traitUsage\PublicTraitWithAttribute;

    final class ExampleUsage
    {
        use GlobalPrivate; // ERROR
        use GlobalProtected; // OK
        use GlobalPublic; // OK
        use GlobalPublicWithAttribute; // OK

        use PrivateTrait; // ERROR
        use ProtectedTrait; // ERROR
        use PublicTrait; // OK
        use PublicTraitWithAttribute; // OK
    }
}

<?php

declare(strict_types=1);

namespace {
    use DaveLiddament\PhpLanguageExtensions\AccessModifier;
    use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;
    use InterfaceImplementation\PrivateInterface;
    use InterfaceImplementation\ProtectedInterface;
    use InterfaceImplementation\PublicInterface;
    use InterfaceImplementation\PublicInterfaceWithAttribute;

    interface GlobalPublicInterface
    {
    }

    #[NamespaceVisibility(AccessModifier::Public)]
    interface GlobalPublicInterfaceWithAttribute
    {
    }

    #[NamespaceVisibility(AccessModifier::Protected)]
    interface GlobalProtectedInterface
    {
    }

    #[NamespaceVisibility(AccessModifier::Private)]
    interface GlobalPrivateInterface
    {
    }

    final class GlobalImplementationOfPublic implements GlobalPublicInterface // OK
    {
    }

    final class GlobalImplementationOfPublicWithAttribute implements GlobalPublicInterfaceWithAttribute // OK
    {
    }

    final class GlobalImplementationOfProtected implements GlobalProtectedInterface // OK
    {
    }

    final class GlobalImplementationOfPrivate implements GlobalPrivateInterface // OK
    {
    }

    final class GlobalImplementationOfNamespacedPublic implements PublicInterface // OK
    {
    }

    final class GlobalImplementationOfNamespacedPublicWithAttribute implements PublicInterfaceWithAttribute // OK
    {
    }

    final class GlobalImplementationOfNamespacedProtected implements ProtectedInterface // ERROR
    {
    }

    final class GlobalImplementationOfNamespacedPrivate implements PrivateInterface // ERROR
    {
    }
}

namespace InterfaceImplementation {
    use DaveLiddament\PhpLanguageExtensions\AccessModifier;
    use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;
    use GlobalPrivateInterface;
    use GlobalProtectedInterface;
    use GlobalPublicInterface;
    use GlobalPublicInterfaceWithAttribute;

    interface PublicInterface
    {
    }

    #[NamespaceVisibility(AccessModifier::Public)]
    interface PublicInterfaceWithAttribute
    {
    }

    #[NamespaceVisibility(AccessModifier::Protected)]
    interface ProtectedInterface
    {
    }

    #[NamespaceVisibility(AccessModifier::Private)]
    interface PrivateInterface
    {
    }

    final class ImplementationOfGlobalPublic implements GlobalPublicInterface // OK
    {
    }

    final class ImplementationOfGlobalPublicWithAttribute implements GlobalPublicInterfaceWithAttribute // OK
    {
    }

    final class ImplementationOfGlobalProtected implements GlobalProtectedInterface // OK
    {
    }

    final class ImplementationOfGlobalPrivate implements GlobalPrivateInterface // ERROR
    {
    }

    final class ImplementationOfPublic implements PublicInterface // OK
    {
    }

    final class ImplementationOfPublicWithAttribute implements PublicInterfaceWithAttribute // OK
    {
    }

    final class ImplementationOfProtected implements ProtectedInterface // OK
    {
    }

    final class ImplementationOfPrivate implements PrivateInterface // OK
    {
    }
}

namespace InterfaceImplementation\Nested {
    use GlobalPrivateInterface;
    use GlobalProtectedInterface;
    use GlobalPublicInterface;
    use GlobalPublicInterfaceWithAttribute;
    use InterfaceImplementation\PrivateInterface;
    use InterfaceImplementation\ProtectedInterface;
    use InterfaceImplementation\PublicInterface;
    use InterfaceImplementation\PublicInterfaceWithAttribute;

    final class ImplementationOfGlobalPublic implements GlobalPublicInterface // OK
    {
    }

    final class ImplementationOfGlobalPublicWithAttribute implements GlobalPublicInterfaceWithAttribute // OK
    {
    }

    final class ImplementationOfGlobalProtected implements GlobalProtectedInterface // OK
    {
    }

    final class ImplementationOfGlobalPrivate implements GlobalPrivateInterface // ERROR
    {
    }

    final class ImplementationOfPublic implements PublicInterface // OK
    {
    }

    final class ImplementationOfPublicWithAttribute implements PublicInterfaceWithAttribute // OK
    {
    }

    final class ImplementationOfProtected implements ProtectedInterface // OK
    {
    }

    final class ImplementationOfPrivate implements PrivateInterface // ERROR
    {
    }
}

namespace InterfaceImplementationInOtherVendor {
    use GlobalPrivateInterface;
    use GlobalProtectedInterface;
    use GlobalPublicInterface;
    use GlobalPublicInterfaceWithAttribute;
    use InterfaceImplementation\PrivateInterface;
    use InterfaceImplementation\ProtectedInterface;
    use InterfaceImplementation\PublicInterface;
    use InterfaceImplementation\PublicInterfaceWithAttribute;

    final class ImplementationOfGlobalPublic implements GlobalPublicInterface // OK
    {
    }

    final class ImplementationOfGlobalPublicWithAttribute implements GlobalPublicInterfaceWithAttribute // OK
    {
    }

    final class ImplementationOfGlobalProtected implements GlobalProtectedInterface // OK
    {
    }

    final class ImplementationOfGlobalPrivate implements GlobalPrivateInterface // ERROR
    {
    }

    final class ImplementationOfPublic implements PublicInterface // OK
    {
    }

    final class ImplementationOfPublicWithAttribute implements PublicInterfaceWithAttribute // OK
    {
    }

    final class ImplementationOfProtected implements ProtectedInterface // ERROR
    {
    }

    final class ImplementationOfPrivate implements PrivateInterface // ERROR
    {
    }
}

<?php
declare(strict_types=1);

namespace VisibilityInconsistencies {

    use DaveLiddament\PhpLanguageExtensions\AccessModifier;
    use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;

    #[NamespaceVisibility(AccessModifier::Public)]
    final class PublicDto
    {
    }

    #[NamespaceVisibility(AccessModifier::Protected)]
    final class ProtectedDto
    {
    }

    #[NamespaceVisibility(AccessModifier::Private)]
    final class PrivateDto
    {
    }

    #[NamespaceVisibility(AccessModifier::Public)]
    interface PublicInterface
    {
        public function arguments(
            PublicDto $public, // OK
            ProtectedDto $protected, // ERROR (it doesn't make sense for public interface to receive non-public args)
            PrivateDto $private, // ERROR (it doesn't make sense for public interface to receive non-public args)
        ): void;

        public function return1(): PublicDto; // OK
        public function return2(): ProtectedDto; // ERROR (it doesn't make sense for public interface to return non-public types)
        public function return3(): PrivateDto; // ERROR (it doesn't make sense for public interface to return non-public types)
    }

    #[NamespaceVisibility(AccessModifier::Protected)]
    interface ProtectedInterface
    {
        public function arguments(
            PublicDto $public, // OK
            ProtectedDto $protected, // OK
            PrivateDto $private, // ERROR (it doesn't make sense for protected interface to receive private args)
        ): void;

        public function return1(): PublicDto; // OK
        public function return2(): ProtectedDto; // OK
        public function return3(): PrivateDto; // ERROR (it doesn't make sense for protected interface to return private types)
    }

    #[NamespaceVisibility(AccessModifier::Private)]
    interface PrivateInterface
    {
        public function arguments(
            PublicDto $public, // OK
            ProtectedDto $protected, // OK
            PrivateDto $private, // OK
        ): void;

        public function return1(): PublicDto; // OK
        public function return2(): ProtectedDto; // OK
        public function return3(): PrivateDto; // OK
    }
}

<?php

declare(strict_types=1);

namespace {
    use DaveLiddament\PhpLanguageExtensions\AccessModifier;
    use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;
    use ReferencesOfClasses\PrivateDto;
    use ReferencesOfClasses\ProtectedDto;
    use ReferencesOfClasses\PublicDto;
    use ReferencesOfClasses\PublicDtoWithAttribute;

    final class GlobalPublicDto
    {
    }

    #[NamespaceVisibility(AccessModifier::Public)]
    final class GlobalPublicDtoWithAttribute
    {
    }

    #[NamespaceVisibility(AccessModifier::Protected)]
    final class GlobalProtectedDto
    {
    }

    #[NamespaceVisibility(AccessModifier::Private)]
    final class GlobalPrivateDto
    {
    }

    interface GlobalVerification
    {
        public function arguments(
            GlobalPublicDto $public, // OK
            GlobalPublicDtoWithAttribute $public2, // OK
            GlobalProtectedDto $protected, // OK
            GlobalPrivateDto $private, // OK

            PublicDto $public3, // OK
            PublicDtoWithAttribute $public4, // OK
            ProtectedDto $protected2, // ERROR
            PrivateDto $private2 // ERROR
        ): void;

        public function return1(): GlobalPublicDto; // OK

        public function return2(): GlobalPublicDtoWithAttribute; // OK

        public function return3(): GlobalProtectedDto; // OK

        public function return4(): GlobalPrivateDto; // OK

        public function return5(): PublicDto; // OK

        public function return6(): PublicDtoWithAttribute; // OK

        public function return7(): ProtectedDto; // ERROR

        public function return8(): PrivateDto; // ERROR
    }
}

namespace ReferencesOfClasses {
    use DaveLiddament\PhpLanguageExtensions\AccessModifier;
    use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;
    use GlobalPrivateDto;
    use GlobalProtectedDto;
    use GlobalPublicDto;
    use GlobalPublicDtoWithAttribute;

    final class PublicDto
    {
    }

    #[NamespaceVisibility(AccessModifier::Public)]
    final class PublicDtoWithAttribute
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

    interface Verification
    {
        public function arguments(
            GlobalPublicDto $public, // OK
            GlobalPublicDtoWithAttribute $public2, // OK
            GlobalProtectedDto $protected, // OK
            GlobalPrivateDto $private, // ERROR

            PublicDto $public3, // OK
            PublicDtoWithAttribute $public4, // OK
            ProtectedDto $protected2, // OK
            PrivateDto $private2 // OK
        ): void;

        public function return1(): GlobalPublicDto; // OK

        public function return2(): GlobalPublicDtoWithAttribute; // OK

        public function return3(): GlobalProtectedDto; // OK

        public function return4(): GlobalPrivateDto; // ERROR

        public function return5(): PublicDto; // OK

        public function return6(): PublicDtoWithAttribute; // OK

        public function return7(): ProtectedDto; // OK

        public function return8(): PrivateDto; // OK
    }
}

namespace ReferencesOfClasses\Nested {
    use GlobalPrivateDto;
    use GlobalProtectedDto;
    use GlobalPublicDto;
    use GlobalPublicDtoWithAttribute;
    use ReferencesOfClasses\PrivateDto;
    use ReferencesOfClasses\ProtectedDto;
    use ReferencesOfClasses\PublicDto;
    use ReferencesOfClasses\PublicDtoWithAttribute;

    interface Verification
    {
        public function arguments(
            GlobalPublicDto $public, // OK
            GlobalPublicDtoWithAttribute $public2, // OK
            GlobalProtectedDto $protected, // OK
            GlobalPrivateDto $private, // ERROR

            PublicDto $public3, // OK
            PublicDtoWithAttribute $public4, // OK
            ProtectedDto $protected2, // OK
            PrivateDto $private2 // ERROR
        ): void;

        public function return1(): GlobalPublicDto; // OK

        public function return2(): GlobalPublicDtoWithAttribute; // OK

        public function return3(): GlobalProtectedDto; // OK

        public function return4(): GlobalPrivateDto; // ERROR

        public function return5(): PublicDto; // OK

        public function return6(): PublicDtoWithAttribute; // OK

        public function return7(): ProtectedDto; // OK

        public function return8(): PrivateDto; // ERROR
    }
}

namespace ReferencesOfClassesInOtherVendor {
    use GlobalPrivateDto;
    use GlobalProtectedDto;
    use GlobalPublicDto;
    use GlobalPublicDtoWithAttribute;
    use ReferencesOfClasses\PrivateDto;
    use ReferencesOfClasses\ProtectedDto;
    use ReferencesOfClasses\PublicDto;
    use ReferencesOfClasses\PublicDtoWithAttribute;

    interface Verification
    {
        public function arguments(
            GlobalPublicDto $public, // OK
            GlobalPublicDtoWithAttribute $public2, // OK
            GlobalProtectedDto $protected, // OK
            GlobalPrivateDto $private, // ERROR

            PublicDto $public3, // OK
            PublicDtoWithAttribute $public4, // OK
            ProtectedDto $protected2, // ERROR
            PrivateDto $private2 // ERROR
        ): void;

        public function return1(): GlobalPublicDto; // OK

        public function return2(): GlobalPublicDtoWithAttribute; // OK

        public function return3(): GlobalProtectedDto; // OK

        public function return4(): GlobalPrivateDto; // ERROR

        public function return5(): PublicDto; // OK

        public function return6(): PublicDtoWithAttribute; // OK

        public function return7(): ProtectedDto; // ERROR

        public function return8(): PrivateDto; // ERROR
    }
}

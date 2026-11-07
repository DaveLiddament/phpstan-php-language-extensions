<?php

declare(strict_types=1);

namespace SealedEdgeCases;

use DaveLiddament\PhpLanguageExtensions\Sealed;

#[Sealed(Permitted::class)]
abstract class SealedClass
{
}

#[Sealed(PermittedInterface::class, PermittedClass::class)]
interface SealedInterface
{
}

#[Sealed]
interface NothingPermitted
{
}

interface OtherInterface
{
}

class Permitted extends SealedClass // OK
{
}

class ChildOfPermitted extends Permitted implements OtherInterface // OK
{
}

interface PermittedInterface extends SealedInterface // OK
{
}

class ImplementsPermittedInterface implements PermittedInterface // OK
{
}

class PermittedClass implements OtherInterface, SealedInterface // OK
{
}

interface AnotherInterface extends OtherInterface, SealedInterface // ERROR SealedEdgeCases\AnotherInterface|extend|SealedEdgeCases\SealedInterface
{
}

#[\Attribute]
class NotPermitted
    extends SealedClass // ERROR SealedEdgeCases\NotPermitted|extend|SealedEdgeCases\SealedClass
    implements
        OtherInterface,
        SealedInterface // ERROR SealedEdgeCases\NotPermitted|implement|SealedEdgeCases\SealedInterface
{
}

class ImplementsNothingPermitted implements NothingPermitted // ERROR SealedEdgeCases\ImplementsNothingPermitted|implement|SealedEdgeCases\NothingPermitted
{
}

function anonymousClass(): object
{
    return new class extends SealedClass {}; // ERROR Anonymous class|extend|SealedEdgeCases\SealedClass
}

<?php

declare(strict_types=1);

namespace SealedClasses;

use DaveLiddament\PhpLanguageExtensions\Sealed;

class Success extends Response // OK
{
}

#[Sealed(Success::class, Failed::class)]
class Response
{
}


class AnotherClass extends Response // ERROR AnotherClass can not extend Response
{
}

class SpecialisedSuccess extends Success // OK Sealed limitations are only limited to classes that directly extend the class marked sealed
{
}
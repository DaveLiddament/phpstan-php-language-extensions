<?php

namespace data;

use DaveLiddament\PhpLanguageExtensions\RestrictTraitTo;

#[RestrictTraitTo(BaseTraitClass::class)]
trait MyTrait
{
}

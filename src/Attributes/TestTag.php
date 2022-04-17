<?php

declare(strict_types=1);

namespace DaveLiddament\PhpLanguageExtensions;

use Attribute;

#[Attribute(Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD)]
class TestTag
{
}

<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Sealed
{
    /** @var array<int,class-string> */
    public array $classes;

    /** @param class-string|array<int,class-string> $classes */
    public function __construct(
        string|array $classes,
    ) {
        $this->classes = is_string($classes) ? [$classes] : $classes;
    }
}

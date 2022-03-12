<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Friend
{
    /** @var array<int,class-string> */
    public array $friends;

    /** @param class-string|array<int,class-string> $friends */
    public function __construct(
        string|array $friends,
    ) {
        $this->friends = is_string($friends) ? [$friends] : $friends;
    }
}

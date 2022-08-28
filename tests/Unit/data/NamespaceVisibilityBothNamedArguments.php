<?php

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Unit\data;

use DaveLiddament\PhpLanguageExtensions\NamespaceVisibility;

#[NamespaceVisibility(namespace: 'foo/bar', excludeSubNamespaces: true)]
class NamespaceVisibilityBothNamedArguments
{
}

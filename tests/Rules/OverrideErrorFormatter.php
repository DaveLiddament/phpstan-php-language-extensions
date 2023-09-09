<?php

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanRuleTestHelper\ErrorMessageFormatter;

final class OverrideErrorFormatter extends ErrorMessageFormatter
{
    public function getErrorMessage(string $errorContext): string
    {
        return "Method {$errorContext} has the Override attribute, but no matching parent method exists";
    }
}

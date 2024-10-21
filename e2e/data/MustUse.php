<?php

namespace data;

use DaveLiddament\PhpLanguageExtensions\MustUseResult;

class MustUse
{
    #[MustUseResult]
    public function getResult(): int
    {
        return 1;
    }

    public function code(): void
    {
        echo $this->getResult();
        $this->getResult();
    }
}

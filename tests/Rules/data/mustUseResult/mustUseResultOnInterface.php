<?php

namespace MustUseResultOnInterface {


    use DaveLiddament\PhpLanguageExtensions\MustUseResult;

    interface BaseClass {

        #[MustUseResult]
        public function mustUseResult(): int;

        public function dontNeedToUseResult(): int;

    }


    class ExtendedOnce implements BaseClass
    {

        public function mustUseResult(): int
        {
            return 1;
        }

        public function dontNeedToUseResult(): int
        {
            return 2;
        }

    }

    class ExtendedTwice extends ExtendedOnce
    {
    }



    $extendedOnce = new ExtendedOnce();

    $extendedOnce->dontNeedToUseResult(); // OK

    $extendedOnce->mustUseResult(); // ERROR

    echo $extendedOnce->mustUseResult(); // OK;

    $value = 1 + $extendedOnce->mustUseResult(); // OK



    $extendedTwice = new ExtendedTwice();

    $extendedTwice->dontNeedToUseResult(); // OK

    $extendedTwice->mustUseResult(); // ERROR

    echo $extendedTwice->mustUseResult(); // OK;

    $value = 1 + $extendedTwice->mustUseResult(); // OK
}
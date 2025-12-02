<?php

namespace MustUseResultOnExtendedClass {


    use DaveLiddament\PhpLanguageExtensions\MustUseResult;

    abstract class BaseClass {

        #[MustUseResult]
        public function mustUseResult(): int
        {
            return 1;
        }

        #[MustUseResult]
        abstract public function abstractMustUseResult(): int;

        public function dontNeedToUseResult(): int
        {
            return 2;
        }
        
        abstract function abstractDontNeedToUseResult(): int;

    }

    
    class ExtendedOnce extends BaseClass 
    {

        public function mustUseResult(): int
        {
            return parent::mustUseResult();
        }

        public function dontNeedToUseResult(): int
        {
            return parent::dontNeedToUseResult();
        }

        public function abstractMustUseResult(): int
        {
            return 1;
        }

        public function abstractDontNeedToUseResult(): int
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

    $extendedOnce->abstractMustUseResult(); // ERROR

    $extendedOnce->abstractDontNeedToUseResult(); // OK

    echo $extendedOnce->mustUseResult(); // OK;

    $value = 1 + $extendedOnce->mustUseResult(); // OK



    $extendedTwice = new ExtendedTwice();

    $extendedTwice->dontNeedToUseResult(); // OK

    $extendedTwice->mustUseResult(); // ERROR

    $extendedTwice->abstractMustUseResult(); // ERROR

    $extendedTwice->abstractDontNeedToUseResult(); // OK

    echo $extendedTwice->mustUseResult(); // OK;

    $value = 1 + $extendedTwice->mustUseResult(); // OK
}
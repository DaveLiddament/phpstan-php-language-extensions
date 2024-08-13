<?php

namespace MustUseResultWithParent {


    use DaveLiddament\PhpLanguageExtensions\MustUseResult;


    class Shape
    {

        public function __construct(
        ) {
        }
    }


    class Circle extends Shape
    {

        public function __construct(
        ) {
            parent::__construct();
        }
    }
}
<?php

namespace data;

class InjectableBad
{
    public function __construct(
        public PersonRepository $repository,
    ) {
    }
}

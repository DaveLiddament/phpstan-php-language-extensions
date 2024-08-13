<?php

namespace data;

class InjectableGood
{
    public function __construct(
        public Repository $repository,
    ) {
    }
}

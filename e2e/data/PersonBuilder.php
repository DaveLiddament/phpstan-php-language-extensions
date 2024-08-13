<?php

namespace data;

class PersonBuilder
{
    public function create(): Person
    {
        return new Person();
    }
}

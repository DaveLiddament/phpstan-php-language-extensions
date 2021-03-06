<?php

namespace PackageOnStaticMethod {

    use DaveLiddament\PhpLanguageExtensions\Package;

    class Person
    {
        #[Package]
        public static function updateName(): void
        {
        }

        public static function update(): void
        {
            Person::updateName(); // OK
        }

        public static function updateSelf(): void
        {
            self::updateName(); // OK
        }
    }

    class Updater
    {
        public function updater(): void
        {
            Person::updateName(); // OK
        }
    }

    Person::updateName(); // OK
}


namespace PackageOnStaticMethod2 {

    use PackageOnStaticMethod\Person;

    class AnotherUpdater
    {
        public function update(): void
        {
            Person::updateName(); // ERROR
        }
    }
}

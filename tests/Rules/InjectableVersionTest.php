<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\InjectableVersionRule;
use PHPStan\Rules\Rule;

/** @extends AbstractInjectableVersionRuleTest<InjectableVersionRule> */
class InjectableVersionTest extends AbstractInjectableVersionRuleTest
{
    protected function getRule(): Rule
    {
        return new InjectableVersionRule($this->createReflectionProvider());
    }

    public function testOnClass(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/injectableVersion/InjectableVersionOnClass.php',
            [
                [
                    30,
                    3,
                    \InjectableVersionOnClass\DoctrineRepository::class,
                    \InjectableVersionOnClass\Repository::class,
                ],
            ],
        );
    }

    public function testExtendThenImplement(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/injectableVersion/InjectableVersionOnExtendsThenImplements.php',
            [
                [
                    25,
                    1,
                    \InjectableVersionOnExtendsThenImplements\AbstractDoctrineRepository::class,
                    \InjectableVersionOnExtendsThenImplements\Repository::class,
                ],
                [
                    30,
                    1,
                    \InjectableVersionOnExtendsThenImplements\DoctrineRepository::class,
                    \InjectableVersionOnExtendsThenImplements\Repository::class,
                ],
            ]
        );
    }

    public function testNoInjectableVersion(): void
    {
        $this->assertNoErrorsReported(__DIR__.'/data/injectableVersion/MultipleLevelsOfInheritanceNoInjectableVersionOnClass.php');
    }

    public function testOnInterface(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/injectableVersion/InjectableVersionOnInterface.php',
            [
                [
                    26,
                    1,
                    \InjectableVersionOnInterface\DoctrineRepository::class,
                    \InjectableVersionOnInterface\Repository::class,
                ],
            ],
        );
    }

    public function testOnMultipleLevelsOfInheritance(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/injectableVersion/MultipleLevelsOfInheritanceInjectableVersionOnInterface.php',
            [
                [
                    31,
                    1,
                    \MultipleLevelsOfInheritanceOnInjectableVersionOnInterface\FirstLevelOfInheritance::class,
                    \MultipleLevelsOfInheritanceOnInjectableVersionOnInterface\CorrectVersion::class,
                ],
                [
                    36,
                    1,
                    \MultipleLevelsOfInheritanceOnInjectableVersionOnInterface\SecondLevelOfInheritance::class,
                    \MultipleLevelsOfInheritanceOnInjectableVersionOnInterface\CorrectVersion::class,
                ],
            ],
        );
    }

    public function testOnNoInjectableVersions(): void
    {
        $this->assertNoErrorsReported(__DIR__.'/data/injectableVersion/MultipleLevelsOfInheritanceNoInjectableVersionOnInterface.php');
    }


}

<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\InjectableVersionRule;
use PHPStan\Rules\Rule;

/** @extends AbstractInjectableVersionRuleTest<InjectableVersionRule> */
class InjectableVersionCheckOnMethodTest extends AbstractInjectableVersionRuleTest
{
    protected function getRule(): Rule
    {
        return new InjectableVersionRule(
            $this->createReflectionProvider(),
            new TestConfig(TestConfig::NONE)
        );
    }

    public function testCheckOnMethod(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/injectableVersion/InjectableVersionCheckOnMethod.php',
            [
                [
                    34,
                    1,
                    \InjectableVersionCheckOnMethod\DoctrineRepository::class,
                    \InjectableVersionCheckOnMethod\Repository::class,
                ],
            ],
        );
    }
}

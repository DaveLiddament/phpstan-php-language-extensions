<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\FriendMethodCallRule;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\SealedClassRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/** @extends RuleTestCase<SealedClassRule> */
class SealedClassTest extends RuleTestCase
{
    protected function getRule(): \PHPStan\Rules\Rule
    {
        return new SealedClassRule(
            $this->createReflectionProvider(),
            new TestConfig(TestConfig::NONE),
        );
    }

    public function testHappyPath(): void
    {
        $this->analyse(
            [
                __DIR__ . '/data/sealed/sealedClasses.php',
            ],
            [

            ],
        );
    }

}

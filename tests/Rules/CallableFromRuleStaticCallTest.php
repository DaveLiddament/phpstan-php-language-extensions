<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\CallableFromStaticCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractCallableFromRuleTest<CallableFromStaticCallRule> */
class CallableFromRuleStaticCallTest extends AbstractCallableFromRuleTest
{
    protected function getRule(): Rule
    {
        return new CallableFromStaticCallRule(
            $this->createReflectionProvider(),
            new TestConfig(TestConfig::NONE),
        );
    }

    public function testStaticCall(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/callableFrom/callableFromOnStaticMethod.php',
            [
                [
                    24,
                    'CallableFromOnStaticMethod\Person::updateName',
                    'CallableFromOnStaticMethod\Updater',
                    'CallableFromOnStaticMethod\CallableFromUpdater',
                ],
                [
                    28,
                    'CallableFromOnStaticMethod\Person::updateName',
                    '<no class>',
                    'CallableFromOnStaticMethod\CallableFromUpdater',
                ],
            ]
        );
    }
}

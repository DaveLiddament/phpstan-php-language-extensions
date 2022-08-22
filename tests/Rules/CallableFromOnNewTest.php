<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\CallableFromNewCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractCallableFromRuleTest<CallableFromNewCallRule> */
class CallableFromOnNewTest extends AbstractCallableFromRuleTest
{
    protected function getRule(): Rule
    {
        return new CallableFromNewCallRule(
            $this->createReflectionProvider(),
            new TestConfig(TestConfig::NONE),
        );
    }

    public function testStaticCall(): void
    {
        $this->assertErrorsReported(
            __DIR__.'/data/callableFrom/callableFromOnNew.php',
            [
                [
                    20,
                    'CallableFromOnNew\Person::__construct',
                    'CallableFromOnNew\Exam',
                    'CallableFromOnNew\PersonBuilder',
                ],
                [
                    24,
                    'CallableFromOnNew\Person::__construct',
                    '<no class>',
                    'CallableFromOnNew\PersonBuilder',
                ],
            ]
        );
    }
}

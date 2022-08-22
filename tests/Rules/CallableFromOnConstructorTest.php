<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Tests\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;
use DaveLiddament\PhpstanPhpLanguageExtensions\Rules\CallableFromNewCallRule;
use PHPStan\Rules\Rule;

/** @extends AbstractCallableFromRuleTest<CallableFromNewCallRule> */
class CallableFromOnConstructorTest extends AbstractCallableFromRuleTest
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
            __DIR__.'/data/callableFrom/callableFromOnConstructor.php',
            [
                [
                    24,
                    'CallableFromOnConstructor\Person::__construct',
                    'CallableFromOnConstructor\Exam',
                    'CallableFromOnConstructor\PersonBuilder',
                ],
                [
                    28,
                    'CallableFromOnConstructor\Person::__construct',
                    '<no class>',
                    'CallableFromOnConstructor\PersonBuilder',
                ],
            ]
        );
    }
}

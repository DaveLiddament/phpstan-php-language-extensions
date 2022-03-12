<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Helpers;

use DaveLiddament\PhpstanPhpLanguageExtensions\Config\TestConfig;

class TestClassChecker
{
    public function __construct(
        private TestConfig $testConfig,
    ) {
    }

    public function isTestClass(?string $namespace, ?string $className): bool
    {
        switch ($this->testConfig->getMode()) {
            case TestConfig::NONE:
                return false;

            case TestConfig::NAMESPACE:
                return $this->checkNamespace($namespace);

            case TestConfig::CLASS_NAME:
                return $this->checkName($className);

            default:
                throw new \LogicException('Unknown test config type.');
        }
    }

    private function checkNamespace(?string $namespace): bool
    {
        if (null === $namespace) {
            return false;
        }

        return str_starts_with(
            haystack: $namespace,
            needle: $this->testConfig->getTestNamespace(),
        );
    }

    private function checkName(?string $className): bool
    {
        if (null === $className) {
            return false;
        }

        return str_ends_with(
            haystack: $className,
            needle: 'Test'
        );
    }
}

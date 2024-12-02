<?php

/**
 * Script takes JSON output from PHPStan and a list of expected errors. It checks this list matches.
 *
 * This should be called from the `test-runner` script.
 */
final class PHPStanResultsChecker
{
    private const IDENTIFIER_PREFIX = 'phpExtensionLibrary.';
    private const FILE_PATH_TO_REMOVE = 'e2e/data/';

    /**
     * @param array<int,string> $expectedResults see `test-runner` script for format
     */
    public function checkResults(string $phpstanResultsAsJsonString, array $expectedResults): void
    {
        $asJson = json_decode($phpstanResultsAsJsonString, true);
        $this->assertArray($asJson, 'Failed to decode PHPStan results');

        $totals = $asJson['totals'] ?? null;
        $this->assertArray($totals, 'Failed to find totals in PHPStan results');

        /** @var int|null $errorCount */
        $errorCount = $totals['errors'] ?? null;
        $this->assertNotNull($errorCount, 'Failed to find error count in PHPStan results');
        if ((int) $errorCount > 0) {
            $errors = $asJson['errors'] ?? null;
            throw new RuntimeException('PHPStan reported errors: '.var_export($errors, true));
        }

        $files = $asJson['files'] ?? null;
        $this->assertArray($files, 'Failed to find files in PHPStan results');

        $additionalReportedErrors = [];

        foreach ($files as $fullFileName => $fileIssues) {
            $filePath = $this->getCleanFilename($fullFileName);

            $this->assertArray($fileIssues, 'Failed to find issues in PHPStan results');
            $messages = $fileIssues['messages'] ?? null;
            $this->assertArray($messages, 'Failed to find messages in PHPStan results');

            /** @var array{identifier?: string, line?: int} $issue */
            foreach ($messages as $issue) {
                $line = $issue['line'] ?? null;
                $identifier = $issue['identifier'] ?? '';
                $cleanIdentifier = str_replace(self::IDENTIFIER_PREFIX, '', $identifier);

                $key = sprintf('%s:%d:%s', $filePath, $line, $cleanIdentifier);

                $expectedResultsKey = array_search($key, $expectedResults, true);
                if (false === $expectedResultsKey) {
                    $additionalReportedErrors[] = $key;
                } else {
                    unset($expectedResults[$expectedResultsKey]);
                }
            }
        }

        if (([] === $additionalReportedErrors) && ([] === $expectedResults)) {
            // ALL OK
            return;
        }

        $errorMessage = implode("\n", [
            'Additional reported errors:',
            var_export($additionalReportedErrors, true),
            'Expected errors not reported:',
            var_export(array_values($expectedResults), true),
        ]);

        throw new RuntimeException($errorMessage);
    }

    /** @phpstan-assert !null $value  */
    private function assertNotNull(mixed $value, string $error): void
    {
        if (null === $value) {
            throw new RuntimeException($error);
        }
    }

    /** @phpstan-assert array<mixed> $value  */
    private function assertArray(mixed $value, string $error): void
    {
        if (!is_array($value)) {
            throw new RuntimeException($error);
        }
    }

    private function getCleanFilename(string $fullFileName): string
    {
        $position = strpos($fullFileName, self::FILE_PATH_TO_REMOVE);
        if (false === $position) {
            throw new RuntimeException('Failed to find '.self::FILE_PATH_TO_REMOVE.' in '.$fullFileName);
        }
        $filePath = substr($fullFileName, $position + strlen(self::FILE_PATH_TO_REMOVE));

        return str_replace('.php', '', $filePath);
    }
}

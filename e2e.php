<?php

/**
 * Add any expected errors here.
 *
 * Format:
 *
 * <file>:<line>:<identifier>
 *
 * Where:
 *
 * `file` is relative to the `build/e2e/data` directory.
 * `line` is the line number in the file.
 * `identifier` is the rule's identifier (NOTE: the prefix `phpExtensionLibrary.` is automatically added)
 */
$expectedErrors = [
    'FriendProblems:9:friend',
    'FriendProblems:10:friend',
    'FriendProblems:11:friend',
    'TraitClassProblems:7:restrictTraitTo',
];

/**
 * Main script.
 */
require_once __DIR__.'/build/e2e/PHPStanResultsChecker.php';
$phpStanResultsChecker = new DaveLiddament\PhpstanPhpLanguageExtensions\Build\e2e\PHPStanResultsChecker();
$stdIn = file_get_contents('php://stdin');

if (false === $stdIn) {
    echo "No input\n";
    exit(2);
}

try {
    $phpStanResultsChecker->checkResults($stdIn, $expectedErrors);
    echo "All OK\n";
    exit(0);
} catch (Exception $e) {
    echo $e->getMessage().\PHP_EOL;
    exit(1);
}

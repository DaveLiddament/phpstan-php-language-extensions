#!/bin/bash

set -e

# Remove any existing composer files from previous run of the script
rm -f composer.* || true

# Create composer.json
cat <<- "EOF" > composer.json
{
    "name": "demo/test_dependencies",
    "repositories" : [
        {
            "type" : "path",
            "url" : "../"
       }
    ]
}
EOF

# Check PHPStan v1 is OK
composer require --dev phpstan/phpstan:^1.0
composer require --dev dave-liddament/phpstan-php-language-extensions @dev
composer update --prefer-lowest --no-interaction

# Check PHPStan v2 is OK
composer require --dev phpstan/phpstan:^2.0

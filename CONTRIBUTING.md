# Contributing

Contributions are welcome. 

## PHP versions supported

This library MUST support all PHP versions that are either in [active or security](https://www.php.net/supported-versions.php) support. 

The maintainer will aim to add support for new PHP versions within 6 weeks of the official release. 

Old versions of PHP MIGHT be dropped once they no longer receive security support.  

## PHPStan versions supported

This library will only support PHPStan v2.


## Code checks

After writing your code, format it according to the project style:

```shell
composer cs-fix
```

Perform all quality assurance tasks that are run for continuous integration: 

```shell
composer ci
```

## Docker setup

[Dockerfile](/Dockerfile) and [docker-compose.yml](/docker-compose.yml) have been provided to help with development. 

### Install dependencies

Run the following to install dependencies in the PHP version of your choice (e.g. 8.4):

```shell
docker compose run --rm php84 composer install
```

### Run composer scripts

You can run composer scripts in any supported PHP version through Docker:

```shell
docker compose run --rm php<version> composer <script> 
```

For example, to run `cs-fix` on PHP 8.2:

```shell
docker compose run --rm php82 composer cs-fix
```

To run all validations in every supported PHP version, you can use the following command:

```shell
for v in 81 82 83 84; do docker compose run --rm php$v composer ci || break; done
```

See [composer.json](/composer.json) section `scripts` for all available scripts.

### Shell access

You can get interactive shell access in any PHP version, e.g. on PHP 8.3:

```shell
docker compose run --rm php83 bash
```

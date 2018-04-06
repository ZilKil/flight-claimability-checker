Flight  claimability checker
===========================

Console command based application which parses provided csv data and decides if flights are eligible for compensation or not.

## Requirements

* PHP 7.1 or higher
* Composer

## Installation

1. Clone code from git
1. Run `composer install` from project root directory

## Sample command

Project contains sample csv file with dummy data to test command. You can run it with the following command:

`$ php bin/console claimability:check app/Resources/sample.csv`

Run it from project root directory or edit path to csv file.

## Tests

You can run test with the following command (again - project root directory is the key to success):

`$ vendor/bin/phpunit`

#!/bin/sh
set -e
XDEBUG_MODE=coverage ./vendor/bin/phpunit --testsuite Unit --coverage-clover 'tests/coverage/coverage.clover.xml'
bin/console global:coverage-checker
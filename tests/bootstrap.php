<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

Phar::loadPhar(__DIR__ . '/../vendor/phpstan/phpstan/phpstan.phar', 'phpstan.phar');
require 'phar://phpstan.phar/vendor/autoload.php';

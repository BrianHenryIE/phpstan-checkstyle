<?php

/**
 * Factory for creating PHPStan error formatter instances.
 *
 * @package BrianHenryIE\PhpstanCheckstyle\ErrorFormatter
 */

declare(strict_types=1);

namespace BrianHenryIE\PhpstanCheckstyle\ErrorFormatter;

use InvalidArgumentException;
use PHPStan\Command\ErrorFormatter\CheckstyleErrorFormatter;
use PHPStan\Command\ErrorFormatter\CiDetectedErrorFormatter;
use PHPStan\Command\ErrorFormatter\ErrorFormatter;
use PHPStan\Command\ErrorFormatter\GithubErrorFormatter;
use PHPStan\Command\ErrorFormatter\GitlabErrorFormatter;
use PHPStan\Command\ErrorFormatter\JsonErrorFormatter;
use PHPStan\Command\ErrorFormatter\JunitErrorFormatter;
use PHPStan\Command\ErrorFormatter\RawErrorFormatter;
use PHPStan\Command\ErrorFormatter\TableErrorFormatter;
use PHPStan\Command\ErrorFormatter\TeamcityErrorFormatter;
use PHPStan\File\SimpleRelativePathHelper;

/**
 * Creates a PHPStan ErrorFormatter for the requested format name.
 */
class FormatterFactory
{
    /**
     * Create a PHPStan error formatter by name.
     *
     * @param string $format One of: table, json, checkstyle, github, gitlab, teamcity, raw, junit.
     *
     * @throws InvalidArgumentException When the format name is not recognised.
     */
    public function create(string $format): ErrorFormatter
    {
        $pathHelper = new SimpleRelativePathHelper(getcwd() ?: '');

        switch ($format) {
            case 'table':
                return new TableErrorFormatter(
                    $pathHelper,
                    $pathHelper,
                    new CiDetectedErrorFormatter(
                        new GithubErrorFormatter($pathHelper),
                        new TeamcityErrorFormatter($pathHelper),
                    ),
                    false,
                    null,
                    null,
                    '0',
                    PHP_INT_MAX,
                );
            case 'json':
                return new JsonErrorFormatter(false);
            case 'checkstyle':
                return new CheckstyleErrorFormatter($pathHelper);
            case 'github':
                return new GithubErrorFormatter($pathHelper);
            case 'gitlab':
                return new GitlabErrorFormatter($pathHelper);
            case 'teamcity':
                return new TeamcityErrorFormatter($pathHelper);
            case 'raw':
                return new RawErrorFormatter();
            case 'junit':
                return new JunitErrorFormatter($pathHelper);
            default:
                throw new InvalidArgumentException("Unknown format: $format");
        }
    }
}

<?php

/**
 * CLI command to convert checkstyle XML to a PHPStan output format.
 *
 * @package BrianHenryIE\PhpstanCheckstyle\Command
 */

declare(strict_types=1);

namespace BrianHenryIE\PhpstanCheckstyle\Command;

use BrianHenryIE\PhpstanCheckstyle\CheckstyleParser;
use BrianHenryIE\PhpstanCheckstyle\ErrorFormatter\FormatterFactory;
use BrianHenryIE\PhpstanCheckstyle\Output\ConsoleOutput;
use BrianHenryIE\PhpstanCheckstyle\Output\ConsoleStyle;
use PHPStan\Command\AnalysisResult;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Invokable command that reads checkstyle XML and outputs it via a PHPStan formatter.
 */
class ConvertCommand
{
    /**
     * Parses checkstyle XML into errors.
     */
    private \BrianHenryIE\PhpstanCheckstyle\CheckstyleParser $parser;

    /**
     * Creates the requested output formatter.
     */
    private \BrianHenryIE\PhpstanCheckstyle\ErrorFormatter\FormatterFactory $formatterFactory;

    /**
     * ConvertCommand constructor.
     *
     * @param CheckstyleParser $parser           Parses checkstyle XML into errors.
     * @param FormatterFactory $formatterFactory Creates the requested output formatter.
     */
    public function __construct(
        CheckstyleParser $parser,
        FormatterFactory $formatterFactory
    ) {
        $this->parser           = $parser;
        $this->formatterFactory = $formatterFactory;
    }

    /**
     * Read checkstyle XML and print it using the requested PHPStan formatter.
     *
     * @param InputInterface  $input  Symfony console input.
     * @param OutputInterface $output Symfony console output.
     * @param string          $format Output format name.
     * @param string|null     $infile Optional path to checkstyle XML file.
     *
     * @return int Exit code.
     */
    public function __invoke(
        InputInterface $input,
        OutputInterface $output,
        string $format = 'table',
        ?string $infile = null
    ): int {
        $xml = $infile !== null
            ? (string) file_get_contents( getcwd() . '/' . ltrim($infile, '\\/' ))
            : (string) stream_get_contents(STDIN);

        $errors = $this->parser->parseXml($xml, $output->isVerbose());

        $analysisResult = new AnalysisResult(
            array_values($errors),
            [],
            [],
            [],
            [],
            false,
            null,
            false,
            0,
            false,
            [],
        );

        $formatter    = $this->formatterFactory->create($format);
        $symfonyStyle = new SymfonyStyle($input, $output);
        $style        = new ConsoleStyle($symfonyStyle);
        $phpstanOut   = new ConsoleOutput($output, $style);

        return $formatter->formatErrors($analysisResult, $phpstanOut);
    }
}

<?php

/**
 * Wraps a Symfony OutputInterface to implement the PHPStan Output interface.
 *
 * @package BrianHenryIE\PhpstanCheckstyle\Output
 */

declare(strict_types=1);

namespace BrianHenryIE\PhpstanCheckstyle\Output;

use PHPStan\Command\Output;
use PHPStan\Command\OutputStyle;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Adapts Symfony OutputInterface to PHPStan's Output contract.
 */
class ConsoleOutput implements Output
{
    /**
     * Symfony console output.
     */
    private \Symfony\Component\Console\Output\OutputInterface $output;

    /**
     * PHPStan output style.
     */
    private \PHPStan\Command\OutputStyle $style;

    /**
     * ConsoleOutput constructor.
     *
     * @param OutputInterface $output Symfony console output.
     * @param OutputStyle     $style  PHPStan output style.
     */
    public function __construct(OutputInterface $output, OutputStyle $style)
    {
        $this->output = $output;
        $this->style  = $style;
    }

    /**
     * Write a formatted message without a trailing newline.
     *
     * @param string $message The message to write.
     */
    public function writeFormatted(string $message): void
    {
        $this->output->write($message, false, OutputInterface::OUTPUT_NORMAL);
    }

    /**
     * Write a formatted message with a trailing newline.
     *
     * @param string $message The message to write.
     */
    public function writeLineFormatted(string $message): void
    {
        $this->output->writeln($message, OutputInterface::OUTPUT_NORMAL);
    }

    /**
     * Write a raw (unformatted) message without a trailing newline.
     *
     * @param string $message The message to write.
     */
    public function writeRaw(string $message): void
    {
        $this->output->write($message, false, OutputInterface::OUTPUT_RAW);
    }

    /**
     * Return the output style helper.
     */
    public function getStyle(): OutputStyle
    {
        return $this->style;
    }

    /**
     * Check whether verbose output is enabled.
     */
    public function isVerbose(): bool
    {
        return $this->output->isVerbose();
    }

    /**
     * Check whether very-verbose output is enabled.
     */
    public function isVeryVerbose(): bool
    {
        return $this->output->isVeryVerbose();
    }

    /**
     * Check whether debug output is enabled.
     */
    public function isDebug(): bool
    {
        return $this->output->isDebug();
    }

    /**
     * Check whether decorated (coloured) output is enabled.
     */
    public function isDecorated(): bool
    {
        return $this->output->isDecorated();
    }
}

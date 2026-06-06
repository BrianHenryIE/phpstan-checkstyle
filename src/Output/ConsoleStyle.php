<?php

/**
 * Wraps a SymfonyStyle instance to implement the PHPStan OutputStyle interface.
 *
 * @package BrianHenryIE\PhpstanCheckstyle\Output
 */

declare(strict_types=1);

namespace BrianHenryIE\PhpstanCheckstyle\Output;

use PHPStan\Command\OutputStyle;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Adapts SymfonyStyle to PHPStan's OutputStyle contract.
 */
class ConsoleStyle implements OutputStyle
{
    /**
     * Symfony style helper.
     */
    private \Symfony\Component\Console\Style\SymfonyStyle $style;

    /**
     * ConsoleStyle constructor.
     *
     * @param SymfonyStyle $style Symfony style helper.
     */
    public function __construct(SymfonyStyle $style)
    {
        $this->style = $style;
    }

    /**
     * Output a title block.
     *
     * @param string $message The title text.
     */
    public function title(string $message): void
    {
        $this->style->title($message);
    }

    /**
     * Output a section heading.
     *
     * @param string $message The section heading text.
     */
    public function section(string $message): void
    {
        $this->style->section($message);
    }

    /**
     * Output a bulleted list.
     *
     * @param string[] $elements List items.
     */
    public function listing(array $elements): void
    {
        $this->style->listing($elements);
    }

    /**
     * Output a success message block.
     *
     * @param string $message The success message.
     */
    public function success(string $message): void
    {
        $this->style->success($message);
    }

    /**
     * Output an error message block.
     *
     * @param string $message The error message.
     */
    public function error(string $message): void
    {
        $this->style->error($message);
    }

    /**
     * Output a warning message block.
     *
     * @param string $message The warning message.
     */
    public function warning(string $message): void
    {
        $this->style->warning($message);
    }

    /**
     * Output a note message block.
     *
     * @param string $message The note message.
     */
    public function note(string $message): void
    {
        $this->style->note($message);
    }

    /**
     * Output a caution message block.
     *
     * @param string $message The caution message.
     */
    public function caution(string $message): void
    {
        $this->style->caution($message);
    }

    /**
     * Render a table.
     *
     * @param string[]  $headers Column headers.
     * @param mixed[][] $rows    Table rows.
     */
    public function table(array $headers, array $rows): void
    {
        $this->style->table($headers, $rows);
    }

    /**
     * Output one or more blank lines.
     *
     * @param int $count Number of blank lines.
     */
    public function newLine(int $count = 1): void
    {
        $this->style->newLine($count);
    }

    /**
     * Start a progress bar.
     *
     * @param int $max Maximum number of steps (0 for indeterminate).
     */
    public function progressStart(int $max = 0): void
    {
        $this->style->progressStart($max);
    }

    /**
     * Advance the progress bar.
     *
     * @param int $step Number of steps to advance.
     */
    public function progressAdvance(int $step = 1): void
    {
        $this->style->progressAdvance($step);
    }

    /**
     * Finish and remove the progress bar.
     */
    public function progressFinish(): void
    {
        $this->style->progressFinish();
    }
}

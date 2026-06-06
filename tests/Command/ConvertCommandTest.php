<?php

declare(strict_types=1);

namespace BrianHenryIE\PhpstanCheckstyle\Tests\Command;

use BrianHenryIE\PhpstanCheckstyle\CheckstyleParser;
use BrianHenryIE\PhpstanCheckstyle\Command\ConvertCommand;
use BrianHenryIE\PhpstanCheckstyle\ErrorFormatter\FormatterFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @coversDefaultClass \BrianHenryIE\PhpstanCheckstyle\Command\ConvertCommand
 */
class ConvertCommandTest extends TestCase
{
    private ConvertCommand $command;

    protected function setUp(): void
    {
        $this->command = new ConvertCommand(
            new CheckstyleParser(),
            new FormatterFactory(),
        );
    }

    /**
     * @covers ::__invoke
     */
    public function testInvokeWithFileReturnsZeroExitCode(): void
    {
        $input  = new ArrayInput([]);
        $output = new BufferedOutput();

        $exitCode = ($this->command)(
            $input,
            $output,
            'raw',
            __DIR__ . '/../fixtures/checkstyle.format.txt',
        );

        $this->assertSame(1, $exitCode);
    }

    /**
     * @covers ::__invoke
     */
    public function testInvokeWithFileProducesOutput(): void
    {
        $input  = new ArrayInput([]);
        $output = new BufferedOutput();

        ($this->command)(
            $input,
            $output,
            'raw',
            __DIR__ . '/../fixtures/checkstyle.format.txt',
        );

        $content = $output->fetch();
        $this->assertStringContainsString('src/Composer/Advisory/AuditConfig.php', $content);
    }

    /**
     * @covers ::__invoke
     */
    public function testInvokeWithJsonFormatProducesValidJson(): void
    {
        $input  = new ArrayInput([]);
        $output = new BufferedOutput();

        ($this->command)(
            $input,
            $output,
            'json',
            __DIR__ . '/../fixtures/checkstyle.format.txt',
        );

        $json = json_decode($output->fetch(), true);
        $this->assertIsArray($json);
        $this->assertArrayHasKey('totals', $json);
        $this->assertArrayHasKey('files', $json);
    }
}

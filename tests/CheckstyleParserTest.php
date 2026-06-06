<?php

declare(strict_types=1);

namespace BrianHenryIE\PhpstanCheckstyle\Tests;

use BrianHenryIE\PhpstanCheckstyle\CheckstyleParser;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \BrianHenryIE\PhpstanCheckstyle\CheckstyleParser
 */
class CheckstyleParserTest extends TestCase
{
    private CheckstyleParser $parser;

    protected function setUp(): void
    {
        $this->parser = new CheckstyleParser();
    }

    /**
     * @covers ::parseFile
     */
    public function testParseFileReturnsCorrectErrorCount(): void
    {
        $errors = $this->parser->parseFile(__DIR__ . '/fixtures/checkstyle.format.txt');

        $this->assertCount(1272, $errors);
    }

    /**
     * @covers ::parseFile
     */
    public function testParseFileReturnsFirstErrorCorrectly(): void
    {
        $errors = $this->parser->parseFile(__DIR__ . '/fixtures/checkstyle.format.txt');

        $first = $errors[0];
        $this->assertSame('src/Composer/Advisory/AuditConfig.php', $first->getFile());
        $this->assertSame(145, $first->getLine());
        $this->assertSame(
            'Part $apply (mixed) of encapsed string cannot be cast to string.',
            $first->getMessage(),
        );
    }

    /**
     * @covers ::parseXml
     */
    public function testParseXmlReturnsCorrectErrorCount(): void
    {
        $xml = (string) file_get_contents(__DIR__ . '/fixtures/checkstyle.format.txt');
        $errors = $this->parser->parseXml($xml);

        $this->assertCount(1272, $errors);
    }
}

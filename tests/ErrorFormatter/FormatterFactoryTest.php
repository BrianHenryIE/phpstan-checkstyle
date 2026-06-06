<?php

declare(strict_types=1);

namespace BrianHenryIE\PhpstanCheckstyle\Tests\ErrorFormatter;

use BrianHenryIE\PhpstanCheckstyle\ErrorFormatter\FormatterFactory;
use InvalidArgumentException;
use PHPStan\Command\ErrorFormatter\CheckstyleErrorFormatter;
use PHPStan\Command\ErrorFormatter\GithubErrorFormatter;
use PHPStan\Command\ErrorFormatter\GitlabErrorFormatter;
use PHPStan\Command\ErrorFormatter\JsonErrorFormatter;
use PHPStan\Command\ErrorFormatter\JunitErrorFormatter;
use PHPStan\Command\ErrorFormatter\RawErrorFormatter;
use PHPStan\Command\ErrorFormatter\TableErrorFormatter;
use PHPStan\Command\ErrorFormatter\TeamcityErrorFormatter;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \BrianHenryIE\PhpstanCheckstyle\ErrorFormatter\FormatterFactory
 */
class FormatterFactoryTest extends TestCase
{
    private FormatterFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new FormatterFactory();
    }

    /**
     * @covers ::create
     * @dataProvider formatterProvider
     * @param class-string<object> $expectedClass
     */
    public function testCreateReturnsCorrectFormatter(string $format, string $expectedClass): void
    {
        $formatter = $this->factory->create($format);

        $this->assertInstanceOf($expectedClass, $formatter);
    }

    /**
     * @return array<string, array{string, class-string}>
     */
    public static function formatterProvider(): array
    {
        return [
            'table'      => ['table', TableErrorFormatter::class],
            'json'       => ['json', JsonErrorFormatter::class],
            'checkstyle' => ['checkstyle', CheckstyleErrorFormatter::class],
            'github'     => ['github', GithubErrorFormatter::class],
            'gitlab'     => ['gitlab', GitlabErrorFormatter::class],
            'teamcity'   => ['teamcity', TeamcityErrorFormatter::class],
            'raw'        => ['raw', RawErrorFormatter::class],
            'junit'      => ['junit', JunitErrorFormatter::class],
        ];
    }

    /**
     * @covers ::create
     */
    public function testCreateThrowsOnUnknownFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown format: bogus');

        $this->factory->create('bogus');
    }
}

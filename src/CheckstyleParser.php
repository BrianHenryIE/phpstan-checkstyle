<?php

/**
 * Parses PHPCodeSniffer checkstyle XML output into PHPStan Error objects.
 *
 * @package BrianHenryIE\PhpstanCheckstyle
 */

declare(strict_types=1);

namespace BrianHenryIE\PhpstanCheckstyle;

use DOMDocument;
use PHPStan\Analyser\Error;

/**
 * Parses checkstyle XML into PHPStan Error objects.
 */
class CheckstyleParser
{
    /**
     * Parse a checkstyle XML file and return PHPStan errors.
     *
     * @param string $filename           Path to the checkstyle XML file.
     * @param bool   $includeIdentifiers Whether to include the source identifier.
     *
     * @return Error[]
     */
    public function parseFile(string $filename, bool $includeIdentifiers = false): array
    {
        $dom = new DOMDocument();
        $dom->load($filename);
        return $this->extractErrors($dom, $includeIdentifiers);
    }

    /**
     * Parse checkstyle XML string and return PHPStan errors.
     *
     * @param string $xml                Checkstyle XML content.
     * @param bool   $includeIdentifiers Whether to include the source identifier.
     *
     * @return Error[]
     */
    public function parseXml(string $xml, bool $includeIdentifiers = false): array
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        return $this->extractErrors($dom, $includeIdentifiers);
    }

    /**
     * Extract PHPStan errors from a parsed DOM document.
     *
     * @param DOMDocument $dom                Parsed checkstyle document.
     * @param bool        $includeIdentifiers Whether to include the source identifier.
     *
     * @return Error[]
     */
    private function extractErrors(DOMDocument $dom, bool $includeIdentifiers): array
    {
        $errors = [];
        foreach ($dom->getElementsByTagName('file') as $file) {
            $filename = $file->getAttribute('name');
            foreach ($file->getElementsByTagName('error') as $error) {
                $source = $error->getAttribute('source');
                $errors[] = new Error(
                    $error->getAttribute('message'),
                    $filename,
                    (int) $error->getAttribute('line'),
                    true,
                    null,
                    null,
                    null,
                    null,
                    null,
                    $includeIdentifiers && $source !== '' ? $source : null,
                );
            }
        }
        return $errors;
    }
}

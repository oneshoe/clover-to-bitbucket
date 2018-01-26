<?php

namespace Oneshoe\CloverToBitbucket;

/**
 * Class Converter
 *
 * @package CloverToBitbucket
 */
class Converter
{
    /**
     * Convert clover xml to bitbucket object.
     *
     * @param \SimpleXMLElement $xml
     * @param string $basePath
     * @return \Oneshoe\CloverToBitbucket\Coverage
     */
    public static function cloverToBitbucket($xml, $basePath)
    {
        $result = new Coverage();

        $fileElements = $xml->xpath('//file');

        foreach ($fileElements as $fileElement) {
            $file = new FileInfo();
            $path = self::makePathRelative($basePath, $fileElement['name']);
            $file->setPath($path);
            $covered = [];
            $unCovered = [];
            foreach ($fileElement->line as $line) {
                if ($line['count'] > 0) {
                    $covered[] = (string)$line['num'];
                } else {
                    $unCovered[] = (string)$line['num'];
                }
            }
            if (count($covered) + count($unCovered) > 0) {
                $coverageString = 'C:'.implode(',', $covered).';';
                $coverageString .= 'P:;';
                $coverageString .= 'U:'.implode(',', $unCovered);
                $file->setCoverage($coverageString);
                $result->addFile($file);
            }
        }

        return $result;
    }

    /**
     *
     * @param $basePath
     * @param $path
     * @return string
     */
    private static function makePathRelative($basePath, $path)
    {
        // Make sure basepath ends with a single separator.
        $basePath = rtrim($basePath, '/').'/';

        // Chop the basepath off the path if it starts with it.
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }

        // Remove any leading separators.
        $path = ltrim($path, '/');

        return $path;
    }
}

<?php

namespace Oneshoe\CloverToBitbucket;

/**
 * Class FileInfo
 *
 * @package Oneshoe\CloverToBitbucket
 */
class FileInfo
{
    /** @var string */
    public $coverage;
    /**
     * @var string
     */
    public $path;


    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getCoverage()
    {
        return $this->coverage;
    }

    /**
     * @param string $coverage
     */
    public function setCoverage($coverage)
    {
        $this->coverage = $coverage;
    }
}

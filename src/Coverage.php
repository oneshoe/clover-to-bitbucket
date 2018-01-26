<?php

namespace Oneshoe\CloverToBitbucket;

/**
 * Class Coverage
 *
 * @package Oneshoe\CloverToBitbucket
 */
class Coverage
{
    /**
     * @var array
     */
    public $files;

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param string $file
     */
    public function addFile($file)
    {
        $this->files[] = $file;
    }
}

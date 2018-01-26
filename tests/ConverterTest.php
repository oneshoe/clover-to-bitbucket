<?php

use Oneshoe\CloverToBitbucket\Converter;

/**
 * Class ConverterTest
 *
 * @covers Oneshoe\CloverToBitbucket\Converter
 */
class ConverterTest extends \PHPUnit\Framework\TestCase
{

    public function testCloverToBitbucket()
    {
        $testData = __DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'basic.xml';
        $cloverXml = new \SimpleXMLElement(file_get_contents($testData));
        $result = Converter::cloverToBitbucket($cloverXml, '/var/www/html');
        $this->assertObjectHasAttribute('files', $result, 'There should be a files attribute.');
        $this->assertCount(2, $result->getFiles(), 'The test data should contain 2 files.');
        $this->assertEquals(
            'web/modules/some.module',
            $result->getFiles()[0]->path,
            'The first path should be web/modules/some.module'
        );
        $this->assertEquals(
            'web/Controller/testing.php',
            $result->getFiles()[1]->path,
            'The second path should be web/Controller/testing.php'
        );
        $this->assertEquals(
            'C:2,3,5,6;P:;U:1,4',
            $result->getFiles()[0]->coverage,
            'The first coverage should be C:2,3,5,6;P:;U:1,4'
        );
        $this->assertEquals(
            'C:21,23,24;P:;U:22',
            $result->getFiles()[1]->coverage,
            'The second path should be C:21,23,24;P:;U:22'
        );
    }
}

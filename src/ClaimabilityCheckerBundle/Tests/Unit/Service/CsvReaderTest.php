<?php

declare(strict_types = 1);

namespace ClaimabilityCheckerBundle\Tests\Unit\Service;


use ClaimabilityCheckerBundle\Exception\InvalidCsvHeadersException;
use ClaimabilityCheckerBundle\Model\FlightDetails;
use ClaimabilityCheckerBundle\Service\CsvReader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class CsvReaderTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $fileSystem;

    public function setUp()
    {
        $directory = [
            'csv' => [
                'no-headers.csv' => "LT,Delay,1\nLT,Delay,2",
                'test.csv'       => "Country,Status,Status details\nLT,Delay,1\nLT,Delay,2",
            ],
        ];

        $this->fileSystem = vfsStream::setup('root', null, $directory);
    }

    public function testReadWithoutHeaders()
    {
        $reader = new CsvReader();

        $this->expectException(InvalidCsvHeadersException::class);

        $reader->read($this->fileSystem->url().'/csv/no-headers.csv');
    }

    public function testRead()
    {
        $reader = new CsvReader();

        $result = $reader->read($this->fileSystem->url().'/csv/test.csv');

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(FlightDetails::class, $result);
    }
}

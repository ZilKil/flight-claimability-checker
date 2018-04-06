<?php

declare(strict_types = 1);

namespace ClaimabilityCheckerBundle\Tests\Unit\Command;


use ClaimabilityCheckerBundle\Chain\DeciderChain;
use ClaimabilityCheckerBundle\Command\ClaimabilityCheckCommand;
use ClaimabilityCheckerBundle\Model\FlightDetails;
use ClaimabilityCheckerBundle\Service\CsvReader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ClaimabilityChechCommandTest extends KernelTestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $fileSystem;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var CsvReader|MockObject
     */
    private $csvReaderMock;

    /**
     * @var DeciderChain|MockObject
     */
    private $deciderChainMock;

    /**
     * @var Command
     */
    private $command;

    public function setUp()
    {
        $directory = [
            'csv' => [
                'test.csv' => '1,a,test',
            ],
        ];

        $this->fileSystem = vfsStream::setup('root', null, $directory);

        $this->csvReaderMock = $this
            ->getMockBuilder(CsvReader::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->deciderChainMock = $this
            ->getMockBuilder(DeciderChain::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->application = new Application(self::bootKernel());
        $this->application->add(new ClaimabilityCheckCommand($this->csvReaderMock, $this->deciderChainMock));

        $this->command = $this->application->find('claimability:check');
    }

    public function testExecuteWithNonExistingFile()
    {
        $commandTester = new CommandTester($this->command);
        $this->expectException(\RuntimeException::class);

        $commandTester->execute([
            'command'           => $this->command->getName(),
            'absolute_csv_path' => $this->fileSystem->url().'/csv/no-file.csv',
        ]);
    }

    public function testExecute()
    {
        $this->csvReaderMock->method('read')->willReturn([
            new FlightDetails(),
            new FlightDetails(),
            new FlightDetails(),
        ]);

        $commandTester = new CommandTester($this->command);

        $commandTester->execute([
            'command'           => $this->command->getName(),
            'absolute_csv_path' => $this->fileSystem->url().'/csv/test.csv',
        ]);

        $output = $commandTester->getDisplay();

        $this->assertContains('Total decisions made: 3', $output);
    }
}

<?php

namespace ClaimabilityCheckerBundle\Command;

use ClaimabilityCheckerBundle\Chain\DeciderChain;
use ClaimabilityCheckerBundle\Model\FlightDecision;
use ClaimabilityCheckerBundle\Service\CsvReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ClaimabilityCheckCommand extends Command
{
    /**
     * @var CsvReader
     */
    private $csvReader;
    /**
     * @var DeciderChain
     */
    private $deciderChain;

    /**
     * ClaimabilityCheckCommand constructor.
     *
     * @param CsvReader    $csvReader
     * @param DeciderChain $deciderChain
     */
    public function __construct(CsvReader $csvReader, DeciderChain $deciderChain)
    {
        $this->csvReader    = $csvReader;
        $this->deciderChain = $deciderChain;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('claimability:check')
            ->setDescription('Parses CSV file and makes flight claimability decisions for each row.')
            ->addArgument('absolute_csv_path', InputArgument::REQUIRED, 'Absolute path to csv file.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);

        $path = $input->getArgument('absolute_csv_path');

        $this->validateFile($path);

        $style->title('Checking claimable flights...');

        $data = $this->csvReader->read($path);

        $style->progressStart(count($data));

        $decisions = [];

        foreach ($data as $flightDetails) {
            $decisions[] = $this->deciderChain->decide($flightDetails);
        }

        $style->progressFinish();

        $style->table(
            array_merge(CsvReader::REQUIRED_HEADER_FIELDS, ['Decision']),
            $this->formatDecisionRows($decisions)
        );

        $style->success(sprintf('Total decisions made: %s', count($data)));
    }

    /**
     * @param string $path
     *
     * @throws \RuntimeException
     */
    private function validateFile(string $path): void
    {
        if (!\file_exists($path) || !\is_readable($path)) {
            throw new \RuntimeException('File does not exist or it is not readable.');
        }
    }

    /**
     * @param FlightDecision[] $decisions
     */
    private function formatDecisionRows(array $decisions)
    {
        $formatted = [];

        foreach ($decisions as $decision) {
            $formatted[] = [
                'country'        => $decision->getDetails()->getCountry(),
                'status'         => $decision->getDetails()->getStatus(),
                'status_details' => $decision->getDetails()->getStatusDetails(),
                'decision'       => $decision->getDecision(),
            ];
        }

        return $formatted;
    }
}

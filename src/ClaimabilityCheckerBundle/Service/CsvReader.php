<?php

declare(strict_types = 1);

namespace ClaimabilityCheckerBundle\Service;


use ClaimabilityCheckerBundle\Exception\InvalidCsvHeadersException;
use ClaimabilityCheckerBundle\Model\FlightDetails;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CsvReader
{
    public const REQUIRED_HEADER_FIELDS = ['Country', 'Status', 'Status details'];

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * CsvReader constructor.
     */
    public function __construct()
    {
        $encoders    = [new CsvEncoder()];
        $normalizers = [new ArrayDenormalizer(), new ObjectNormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @param string $path
     *
     * @return FlightDetails[]
     * @throws InvalidCsvHeadersException
     */
    public function read(string $path): array
    {
        $contents = file_get_contents($path);

        $decoded = $this->serializer->decode($contents, CsvEncoder::FORMAT);

        if (!$this->isCollection($decoded)) {
            $decoded = [$decoded];
        }

        $this->validateHeaderFields($decoded);

        return $this->serializer->denormalize($decoded, FlightDetails::class.'[]', CsvEncoder::FORMAT);
    }

    /**
     * @param array $decoded
     *
     * @throws InvalidCsvHeadersException
     */
    private function validateHeaderFields(array $decoded)
    {
        $first = reset($decoded);

        foreach (self::REQUIRED_HEADER_FIELDS as $required) {
            if (!isset($first[$required])) {
                throw new InvalidCsvHeadersException(
                    sprintf(
                        'Invalid csv headers provided. First line of file should contain [%s] fields.',
                        implode(', ', self::REQUIRED_HEADER_FIELDS)
                    )
                );
            }
        }
    }

    /**
     * Checks if decoded array is collection of rows
     *
     * @param array $decoded
     *
     * @return bool
     */
    private function isCollection(array $decoded): bool
    {
        return array_keys($decoded) === range(0, count($decoded) - 1);
    }
}

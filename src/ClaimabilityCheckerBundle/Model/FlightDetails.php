<?php

declare(strict_types = 1);

namespace ClaimabilityCheckerBundle\Model;


class FlightDetails
{
    public const STATUS_CANCEL = 'Cancel';
    public const STATUS_DELAY  = 'Delay';

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $status;

    /**
     * @var integer
     */
    private $statusDetails;

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return self
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatusDetails(): int
    {
        return $this->statusDetails;
    }

    /**
     * @param int $statusDetails
     *
     * @return self
     */
    public function setStatusDetails(int $statusDetails): self
    {
        $this->statusDetails = $statusDetails;

        return $this;
    }
}

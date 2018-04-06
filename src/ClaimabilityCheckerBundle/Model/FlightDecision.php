<?php

declare(strict_types = 1);

namespace ClaimabilityCheckerBundle\Model;


class FlightDecision
{
    public const CLAIMABLE     = 'Y';
    public const NOT_CLAIMABLE = 'N';

    /**
     * @var FlightDetails
     */
    private $details;

    /**
     * @var string
     */
    private $decision;

    /**
     * FlightDecision constructor.
     *
     * @param FlightDetails $details
     * @param bool          $claimable
     */
    public function __construct(FlightDetails $details, bool $claimable)
    {
        $this->details = $details;
        $this->decision = $claimable ? self::CLAIMABLE : self::NOT_CLAIMABLE;
    }

    /**
     * @return FlightDetails
     */
    public function getDetails(): FlightDetails
    {
        return $this->details;
    }

    /**
     * @return string
     */
    public function getDecision(): string
    {
        return $this->decision;
    }
}

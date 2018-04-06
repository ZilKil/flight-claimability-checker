<?php

declare(strict_types = 1);

namespace ClaimabilityCheckerBundle\Service\Decider;


use ClaimabilityCheckerBundle\Model\FlightDecision;
use ClaimabilityCheckerBundle\Model\FlightDetails;

interface DeciderInterface
{
    /**
     * @param FlightDetails $flightDetails
     *
     * @return bool
     */
    public function supports(FlightDetails $flightDetails): bool;

    /**
     * @param FlightDetails $flightDetails
     *
     * @return FlightDecision
     */
    public function decide(FlightDetails $flightDetails): ?FlightDecision;
}

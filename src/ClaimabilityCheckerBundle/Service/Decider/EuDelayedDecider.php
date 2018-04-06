<?php

declare(strict_types = 1);

namespace ClaimabilityCheckerBundle\Service\Decider;


use ClaimabilityCheckerBundle\Helper\CountryHelper;
use ClaimabilityCheckerBundle\Model\FlightDecision;
use ClaimabilityCheckerBundle\Model\FlightDetails;

class EuDelayedDecider implements DeciderInterface
{
    private const HOURS_LATE_THRESHOLD = 3;

    /**
     * @param FlightDetails $flightDetails
     *
     * @return bool
     */
    public function supports(FlightDetails $flightDetails): bool
    {
        return $flightDetails->getStatus() === FlightDetails::STATUS_DELAY
            && CountryHelper::isCountryInEu($flightDetails->getCountry());
    }

    /**
     * @param FlightDetails $flightDetails
     *
     * @return FlightDecision
     */
    public function decide(FlightDetails $flightDetails): ?FlightDecision
    {
        return $this->isClaimable($flightDetails)
            ? new FlightDecision($flightDetails, true)
            : null;
    }

    /**
     * @param FlightDetails $flightDetails
     *
     * @return bool
     */
    private function isClaimable(FlightDetails $flightDetails): bool
    {
        return $flightDetails->getStatusDetails() >= self::HOURS_LATE_THRESHOLD;
    }
}

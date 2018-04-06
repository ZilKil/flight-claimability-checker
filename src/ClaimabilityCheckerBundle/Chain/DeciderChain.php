<?php

declare(strict_types = 1);

namespace ClaimabilityCheckerBundle\Chain;


use ClaimabilityCheckerBundle\Model\FlightDecision;
use ClaimabilityCheckerBundle\Model\FlightDetails;
use ClaimabilityCheckerBundle\Service\Decider\DeciderInterface;

class DeciderChain
{
    /**
     * @var DeciderInterface[]
     */
    private $deciders = [];

    /**
     * @param DeciderInterface $decider
     */
    public function registerDecider(DeciderInterface $decider): void
    {
        $this->deciders[] = $decider;
    }

    /**
     * @param FlightDetails $flightDetails
     *
     * @return FlightDecision
     */
    public function decide(FlightDetails $flightDetails): FlightDecision
    {
        foreach ($this->deciders as $decider) {
            if ($decider->supports($flightDetails) && $decision = $decider->decide($flightDetails)) {
                return $decision;
            }
        }

        return new FlightDecision($flightDetails, false);
    }
}

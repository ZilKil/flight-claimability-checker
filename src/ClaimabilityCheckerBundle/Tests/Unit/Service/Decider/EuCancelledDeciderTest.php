<?php

declare(strict_types = 1);

namespace ClaimabilityCheckerBundle\Tests\Unit\Service\Decider;


use ClaimabilityCheckerBundle\Model\FlightDecision;
use ClaimabilityCheckerBundle\Model\FlightDetails;
use ClaimabilityCheckerBundle\Service\Decider\EuCancelledDecider;
use PHPUnit\Framework\TestCase;

class EuCancelledDeciderTest extends TestCase
{
    public function testSupportsWithUnsupportedStatus()
    {
        $details = new FlightDetails();
        $details->setStatus('unsupported');

        $this->assertFalse($this->getDecider()->supports($details));
    }

    public function testSupportsWithNonEuCountry()
    {
        $details = new FlightDetails();
        $details
            ->setStatus(FlightDetails::STATUS_CANCEL)
            ->setCountry('RU');

        $this->assertFalse($this->getDecider()->supports($details));
    }

    public function testSupports()
    {
        $details = new FlightDetails();
        $details
            ->setStatus(FlightDetails::STATUS_CANCEL)
            ->setCountry('LT');

        $this->assertTrue($this->getDecider()->supports($details));
    }

    public function testDecideWithTooManyDaysToFlight()
    {
        $details = new FlightDetails();
        $details
            ->setStatusDetails(20);

        $result = $this->getDecider()->decide($details);

        $this->assertNull($result);
    }

    public function testDecide()
    {
        $details = new FlightDetails();
        $details
            ->setStatusDetails(10);

        $result = $this->getDecider()->decide($details);

        $this->assertEquals(FlightDecision::CLAIMABLE, $result->getDecision());
    }

    /**
     * @return EuCancelledDecider
     */
    private function getDecider(): EuCancelledDecider
    {
        return new EuCancelledDecider();
    }
}

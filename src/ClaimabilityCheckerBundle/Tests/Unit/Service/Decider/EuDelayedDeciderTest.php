<?php

declare(strict_types = 1);

namespace ClaimabilityCheckerBundle\Tests\Unit\Service\Decider;


use ClaimabilityCheckerBundle\Model\FlightDecision;
use ClaimabilityCheckerBundle\Model\FlightDetails;
use ClaimabilityCheckerBundle\Service\Decider\EuDelayedDecider;
use PHPUnit\Framework\TestCase;

class EuDelayedDeciderTest extends TestCase
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
            ->setStatus(FlightDetails::STATUS_DELAY)
            ->setCountry('RU');

        $this->assertFalse($this->getDecider()->supports($details));
    }

    public function testSupports()
    {
        $details = new FlightDetails();
        $details
            ->setStatus(FlightDetails::STATUS_DELAY)
            ->setCountry('LT');

        $this->assertTrue($this->getDecider()->supports($details));
    }

    public function testDecideWithDelayUnderThreshold()
    {
        $details = new FlightDetails();
        $details
            ->setStatusDetails(1);

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
     * @return EuDelayedDecider
     */
    private function getDecider(): EuDelayedDecider
    {
        return new EuDelayedDecider();
    }
}

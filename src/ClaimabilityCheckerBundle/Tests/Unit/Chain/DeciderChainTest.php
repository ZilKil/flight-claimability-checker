<?php

declare(strict_types = 1);

namespace ClaimabilityCheckerBundle\Tests\Unit\Chain;


use ClaimabilityCheckerBundle\Chain\DeciderChain;
use ClaimabilityCheckerBundle\Model\FlightDecision;
use ClaimabilityCheckerBundle\Model\FlightDetails;
use ClaimabilityCheckerBundle\Service\Decider\DeciderInterface;
use PHPUnit\Framework\TestCase;

class DeciderChainTest extends TestCase
{
    public function testDecide()
    {
        $decider1 = $this->createMock(DeciderInterface::class);
        $decider2 = $this->createMock(DeciderInterface::class);
        $decider3 = $this->createMock(DeciderInterface::class);

        $chain = new DeciderChain();

        $chain->registerDecider($decider1);
        $chain->registerDecider($decider2);
        $chain->registerDecider($decider3);

        $details = new FlightDetails();
        $decision = new FlightDecision($details, true);

        $decider1->expects($this->once())->method('supports')->willReturn(false);
        $decider1->expects($this->never())->method('decide');

        $decider2->expects($this->once())->method('supports')->willReturn(true);
        $decider2->expects($this->once())->method('decide')->willReturn($decision);

        $decider3->expects($this->never())->method('supports');
        $decider3->expects($this->never())->method('decide');

        $chain->decide($details);
    }
}

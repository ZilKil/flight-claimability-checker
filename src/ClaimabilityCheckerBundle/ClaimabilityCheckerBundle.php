<?php

namespace ClaimabilityCheckerBundle;

use ClaimabilityCheckerBundle\DependencyInjection\Compiler\ClaimabilityDecidersPass;
use ClaimabilityCheckerBundle\Service\Decider\DeciderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ClaimabilityCheckerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ClaimabilityDecidersPass());
    }
}

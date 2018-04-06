<?php

namespace ClaimabilityCheckerBundle\DependencyInjection\Compiler;

use Application\ProvidersBundle\Chain\LowestPriceImporterChain;
use ClaimabilityCheckerBundle\Chain\DeciderChain;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;

class ClaimabilityDecidersPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @throws InvalidArgumentException
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(DeciderChain::class)) {
            return;
        }

        $definition = $container->findDefinition(DeciderChain::class);
        $taggedServices = $container->findTaggedServiceIds('claimability.decider');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('registerDecider', [new Reference($id),]);
        }
    }
}

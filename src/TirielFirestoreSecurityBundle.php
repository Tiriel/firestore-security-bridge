<?php

namespace Tiriel\FirestoreSecurityBridge;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class TirielFirestoreSecurityBundle extends AbstractBundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');

        $container->services()
            ->get('tiriel.firestore_security.user_provider')
            ->arg(0, new ReferenceConfigurator($config['user_manager']))
        ;
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->scalarNode('user_manager')->end()
            ->end()
        ;
    }
}

<?php

namespace Adiuvo\Bundle\SoftDeleteableExtensionBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SoftDeleteableExtensionExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.php');
    }
}
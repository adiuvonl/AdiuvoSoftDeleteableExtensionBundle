<?php

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

$container
    ->setDefinition('adiuvo.softdeletabble.listener.softdelete', new Definition('Adiuvo\Bundle\SoftDeleteableExtensionBundle\EventListener\SoftDeleteListener', []))
    ->addMethodCall('setContainer', [
        new Reference('service_container')
    ])
    ->addTag('doctrine.event_listener', [
        'event' => 'preSoftDelete'
    ])
;
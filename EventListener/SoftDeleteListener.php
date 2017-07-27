<?php


namespace Adiuvo\Bundle\SoftDeleteableExtensionBundle\EventListener;

use Adiuvo\Bundle\SoftDeleteableExtensionBundle\Exception\OnSoftDeleteCascadeException;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\PropertyAccess\PropertyAccess;

class SoftDeleteListener
{
    use ContainerAwareTrait;

    /**
     * @param LifecycleEventArgs $args
     * @throws OnSoftDeleteCascadeException
     */
    public function preSoftDelete(LifecycleEventArgs $args)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $mappings = [];

        foreach ($this->getClassMetaData($args)->getAssociationMappings() as $associationMapping) {
            if ($propertyAccessor->isReadable($args->getEntity(), $associationMapping['fieldName']) === false) {
                continue;
            }

            foreach ($propertyAccessor->getValue($args->getEntity(), $associationMapping['fieldName']) as $mapping) {
                $mappings[] = $mapping;
            }
        }

        if (count($mappings) > 0) {
            $exception = new OnSoftDeleteCascadeException();
            $exception->setMappings($mappings);

            throw $exception;
        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @return \Doctrine\ORM\Mapping\ClassMetadata
     */
    private function getClassMetaData(LifecycleEventArgs $args)
    {
        return $args->getEntityManager()->getClassMetadata(get_class($args->getEntity()));
    }
}
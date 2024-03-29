<?php


namespace Adiuvo\Bundle\SoftDeleteableExtensionBundle\EventListener;

use Adiuvo\Bundle\SoftDeleteableExtensionBundle\Exception\OnSoftDeleteCascadeException;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteable;
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

        /** @var SoftDeleteable $entity */
        $entity = $args->getEntity();

        $mappings = [];

        foreach ($this->getClassMetaData($args)->getAssociationMappings() as $associationMapping) {
            if ($associationMapping['type'] === ClassMetadataInfo::MANY_TO_ONE) {
                continue;
            }

            if ($propertyAccessor->isReadable($entity, $associationMapping['fieldName']) === false) {
                continue;
            }

            foreach ($propertyAccessor->getValue($entity, $associationMapping['fieldName']) as $mapping) {
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
        /** @var SoftDeleteable $entity */
        $entity = $args->getEntity();

        return $args->getEntityManager()->getClassMetadata(get_class($entity));
    }
}
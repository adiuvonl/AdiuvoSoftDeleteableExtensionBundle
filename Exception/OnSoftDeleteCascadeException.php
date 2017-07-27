<?php

namespace Adiuvo\Bundle\SoftDeleteableExtensionBundle\Exception;

class OnSoftDeleteCascadeException extends \Exception
{
    private $mappings;

    /**
     * @param array $mappings
     */
    public function setMappings(array $mappings)
    {
        $this->mappings = $mappings;
    }

    /**
     * @return mixed
     */
    public function getMappings()
    {
        return $this->mappings;
    }
}
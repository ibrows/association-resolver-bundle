<?php

namespace Ibrows\AssociationResolver\Reader;

use Ibrows\AssociationResolver\Annotation\AssociationInterface;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;

interface AssociationMappingInformationInterface
{
    /**
     * @return AssociationInterface
     */
    public function getAnnotation();

    /**
     * @return ClassMetadata
     */
    public function getMetaData();
}
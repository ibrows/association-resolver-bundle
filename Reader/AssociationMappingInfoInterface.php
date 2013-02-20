<?php

namespace Ibrows\AssociationResolver\Reader;

use Ibrows\AssociationResolver\Annotation\AssociationInterface;

interface AssociationMappingInfoInterface
{
    /**
     * @return AssociationInterface
     */
    public function getAnnotation();

    /**
     * @return array
     */
    public function getMetaData();
}
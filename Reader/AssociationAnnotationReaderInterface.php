<?php

namespace Ibrows\AssociationResolver\Reader;

use Ibrows\AssociationResolver\Reader\AssociationMappingInfoInterface;

use Ibrows\AnnotationReader\AnnotationReaderInterface;
use Doctrine\ORM\EntityManager;

interface AssociationAnnotationReaderInterface extends AnnotationReaderInterface
{
    const
        ANNOTATION_TYPE_ASSOCIATION = 'AssociationInterface'
    ;

    /**
     * @param EntityManager $entityManager
     * @return AnnotationReaderInterface
     */
    public function setEntityManager(EntityManager $entityManager);

    /**
     * @param string $className
     * @return AssociationMappingInfoInterface[]
     */
    public function getAssociationAnnotations($className);
}
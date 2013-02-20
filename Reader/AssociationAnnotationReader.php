<?php

namespace Ibrows\AssociationResolver\Reader;

use Ibrows\AnnotationReader\AnnotationReader;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;

class AssociationAnnotationReader extends AnnotationReader implements AssociationAnnotationReaderInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     * @return AssociationAnnotationReader
     */
    public function setEntityManager(EntityManager $entityManager){
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @param string $className
     * @return AssociationMappingInformationInterface[]
     */
    public function getAssociationAnnotations($className)
    {
        $metaData = $this->getMetaData($className);
        $annotations = $this->getAnnotationsByType($className, self::ANNOTATION_TYPE_ASSOCIATION, self::SCOPE_PROPERTY);
        
        $associationAnnotations = array();

        foreach($annotations as $fieldName => $annotation){
            $associationAnnotations[$fieldName] = new AssociationMappingInformation(
                $annotation, $metaData->associationMappings[$fieldName]
            );
        }
        
        return $associationAnnotations;
    }

    /**
     * @param $className
     * @return ClassMetadata
     */
    protected function getMetaData($className)
    {
        return $this->entityManager->getMetadataFactory()->getMetadataFor($className);
    }
}
<?php

namespace Ibrows\AssociationResolver\Reader;

use Ibrows\AssociationResolver\Annotation\AssociationInterface;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;

class AssociationMappingInformation implements AssociationMappingInformationInterface
{
    /**
     * @var AssociationInterface
     */
    protected $annotation;

    /**
     * @var ClassMetadata
     */
    protected $metaData;

    /**
     * @param AssociationInterface $annotation
     * @param ClassMetadata $metaData
     */
    public function __construct(AssociationInterface $annotation, ClassMetadata $metaData)
    {
        $this->annotation = $annotation;
        $this->metaData = $metaData;
    }

    /**
     * @return AssociationInterface
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * @return ClassMetadata
     */
    public function getMetaData()
    {
        return $this->metaData;
    }
}
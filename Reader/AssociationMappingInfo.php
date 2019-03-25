<?php

namespace Ibrows\AssociationResolver\Reader;

use Ibrows\AssociationResolver\Annotation\AssociationInterface;

class AssociationMappingInfo implements AssociationMappingInfoInterface
{
    /**
     * @var AssociationInterface
     */
    protected $annotation;

    /**
     * @var array
     */
    protected $metaData;

    /**
     * @param AssociationInterface $annotation
     * @param array $metaData
     */
    public function __construct(AssociationInterface $annotation, array $metaData)
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
     * @return array
     */
    public function getMetaData()
    {
        return $this->metaData;
    }
}

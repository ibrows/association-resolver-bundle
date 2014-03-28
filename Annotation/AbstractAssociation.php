<?php

namespace Ibrows\AssociationResolver\Annotation;

abstract class AbstractAssociation implements AssociationInterface
{
    /**
     * @var string
     */
    public $targetFieldName;

    /**
     * @var string
     */
    public $valueFieldName;

    /**
     * @var string
     */
    public $entitySetterName = null;

    /**
     * @var string
     */
    public $entityGetterName = null;

    /**
     * @var string
     */
    public $valueGetterName = null;

    /**
     * @var string
     */
    public $targetEntity = null;

    /**
     * @return string
     */
    public function getTargetFieldName()
    {
        return $this->targetFieldName;
    }

    /**
     * @return string
     */
    public function getValueFieldName()
    {
        return $this->valueFieldName;
    }

    /**
     * @return string|null
     */
    public function getEntitySetterName()
    {
        return $this->entitySetterName;
    }

    /**
     * @return string|null
     */
    public function getEntityGetterName()
    {
        return $this->entityGetterName;
    }

    /**
     * @return string|null
     */
    public function getValueGetterName()
    {
        return $this->valueGetterName;
    }

    /**
     * @return string
     */
    public function getTargetEntity()
    {
        return $this->targetEntity;
    }

    /**
     * @return string
     */
    public function getType()
    {
        $explode = explode("\\", get_class($this));
        return end($explode);
    }
}
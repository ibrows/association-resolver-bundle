<?php

namespace Ibrows\AssociationResolver\Annotation;

interface AssociationInterface
{
    /**
     * @return string
     */
    public function getTargetFieldName();

    /**
     * @return string
     */
    public function getValueFieldName();

    /**
     * @return string|null
     */
    public function getEntitySetterName();

    /**
     * @return string|null
     */
    public function getEntityGetterName();

    /**
     * @return string|null
     */
    public function getValueGetterName();

    /**
     * @return string
     */
    public function getType();
}

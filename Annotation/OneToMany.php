<?php
/**
 * Created by PhpStorm.
 * User: Faebeee
 * Date: 22.05.14
 * Time: 16:31
 */

namespace Ibrows\AssociationResolver\Annotation;

/**
 * Class OneToMany
 * @package Ibrows\AssociationResolver\Annotation
 * @Annotation
 */
class OneToMany extends AbstractAssociation
{
    public $collectionAddFunctionName;
    public $collectionRemoveFunctionName;

    /**
     * @param mixed $collectionAddFunctionName
     */
    public function setCollectionAddFunctionName($collectionAddFunctionName)
    {
        $this->collectionAddFunctionName = $collectionAddFunctionName;
    }

    /**
     * @return mixed
     */
    public function getCollectionAddFunctionName()
    {
        return $this->collectionAddFunctionName;
    }

    /**
     * @param mixed $collectionRemoveFunctionName
     */
    public function setCollectionRemoveFunctionName($collectionRemoveFunctionName)
    {
        $this->collectionRemoveFunctionName = $collectionRemoveFunctionName;
    }

    /**
     * @return mixed
     */
    public function getCollectionRemoveFunctionName()
    {
        return $this->collectionRemoveFunctionName;
    }
}

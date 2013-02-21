<?php

namespace Ibrows\AssociationResolver\Resolver\Type;

use Ibrows\AssociationResolver\Result\ResultBag;
use Ibrows\AssociationResolver\Reader\AssociationMappingInfoInterface;
use Ibrows\AssociationResolver\Exception\MethodNotFoundException;

use Doctrine\ORM\EntityManager;

abstract class AbstractResolver implements ResolverInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param ResultBag $resultBag
     * @param AssociationMappingInfoInterface $mappingInfo
     * @param string $propertyName
     * @param mixed $entity
     * @return bool
     */
    public function isResponsible(
        ResultBag $resultBag,
        AssociationMappingInfoInterface $mappingInfo,
        $propertyName,
        $entity
    ){
        return $mappingInfo->getAnnotation()->getType() == $this->getType();
    }

    /**
     * @return string
     */
    protected function getType()
    {
        $className = get_class($this);
        $explode = explode('\\', $className);
        return end($explode);
    }

    /**
     * @param array $methods
     * @param mixed $entity
     * @throws MethodNotFoundException
     */
    protected function checkIfMethodsExists(array $methods, $entity)
    {
        foreach($methods as $methodName){
            if(!method_exists($entity, $methodName)){
                throw new MethodNotFoundException(sprintf(
                    'Method "%s" not found but needed',
                    get_class($entity).'::'.$methodName.'()'
                ));
            }
        }
    }
}
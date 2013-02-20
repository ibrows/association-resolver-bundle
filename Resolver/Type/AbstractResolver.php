<?php

namespace Ibrows\AssociationResolver\Resolver\Type;

use Ibrows\AssociationResolver\Exception\MethodNotFoundException;
use Doctrine\ORM\EntityManager;

abstract class AbstractResolver implements ResolverInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
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
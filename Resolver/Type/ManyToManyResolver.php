<?php

namespace Ibrows\AssociationResolver\Resolver\Type;

use Ibrows\AssociationResolver\Result\ResultBag;
use Ibrows\AssociationResolver\Reader\AssociationMappingInformationInterface;
use Ibrows\AssociationResolver\Exception\MethodNotFoundException;

class ManyToManyResolver extends AbstractResolver
{
    /**
     * @param ResultBag $resultBag
     * @param AssociationMappingInformationInterface $mappingInformation
     * @param string $propertyName
     * @param mixed $entity
     * @throws MethodNotFoundException
     */
    public function resolveAssociation(
        ResultBag $resultBag,
        AssociationMappingInformationInterface $mappingInformation,
        $propertyName,
        $entity
    )
    {
        $manyToMany = $mappingInformation->getAnnotation();
        $metaData = $mappingInformation->getMetaData();

        $methods = array(
            'setEntity' => $manyToMany->getEntitySetterName() ?: 'set'. ucfirst($propertyName),
            'getEntity' => $manyToMany->getEntityGetterName() ?: 'get'. ucfirst($propertyName),
            'getValue' => $manyToMany->getValueGetterName() ?: 'get'. ucfirst($manyToMany->getValueFieldName())
        );

        foreach($methods as $method){
            if(!method_exists($entity, $method)){
                throw new MethodNotFoundException(sprintf('Method "%s" not found in "%s"', $method, $entity));
            }
        }

        $getSearchFieldValueMethod = $methods['getValue'];

        $targetClassRepo = $this->entityManager->getRepository($metaData['targetEntity']);
        $criterias = array(
            $manyToMany->getTargetFieldName() => $entity->$getSearchFieldValueMethod()
        );

        $targetEntities = $targetClassRepo->findBy($criterias);
        if($targetEntities instanceof Collection){
            $targetEntities = $targetEntities->toArray();
        }

        $getCurrentTargetEntitiesMethod = $methods['getEntity'];
        $currentTargetEntities = $entity->$getCurrentTargetEntitiesMethod();
        if($currentTargetEntities instanceof Collection){
            $currentTargetEntities = $currentTargetEntities->toArray();
        }

        $hasDelta = false;

        foreach($currentTargetEntities as $currentTargetEntity){
            if(!in_array($currentTargetEntity, $targetEntities) OR ($currentTargetEntity && $currentTargetEntity->getDeletedAt() !== null)){
                $hasDelta = true;
            }
        }

        if(!$hasDelta){
            foreach($targetEntities as $targetEntity){
                if(!in_array($targetEntity, $currentTargetEntities) OR ($targetEntity && $targetEntity->getDeletedAt() !== null)){
                    $hasDelta = true;
                }
            }
        }

        if($hasDelta){
            $setTargetEntityMethod = $methods['setEntity'];
            $entity->$setTargetEntityMethod($targetEntities);

            $resultBag->addChanged($entity);
        }else{
            $resultBag->addSkipped($entity);
        }
    }
}
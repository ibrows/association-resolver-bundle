<?php

namespace Ibrows\AssociationResolver\Resolver\Type;

use Ibrows\AssociationResolver\Result\ResultBag;
use Ibrows\AssociationResolver\Reader\AssociationMappingInformationInterface;
use Ibrows\AssociationResolver\Exception\MethodNotFoundException;

class ManyToOneResolver extends AbstractResolver
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
        $manyToOne = $mappingInformation->getAnnotation();
        $metaData = $mappingInformation->getMetaData();

        $methods = array(
            'setEntity' => $manyToOne->getEntitySetterName() ?: 'set'. ucfirst($propertyName),
            'getEntity' => $manyToOne->getEntityGetterName() ?: 'get'. ucfirst($propertyName),
            'getValue' => $manyToOne->getValueGetterName() ?: 'get'. ucfirst($manyToOne->getValueFieldName())
        );

        $this->checkIfMethodsExists($methods, $entity);

        $getSearchFieldValueMethod = $methods['getValue'];
        $targetClassRepo = $this->entityManager->getRepository($metaData['targetEntity']);

        $criterias = array(
            $manyToOne->getTargetFieldName() => $entity->$getSearchFieldValueMethod()
        );

        $targetEntity = $targetClassRepo->findOneBy($criterias);

        $getCurrentTargetEntityMethod = $methods['getEntity'];
        $currentTargetEntity = $entity->$getCurrentTargetEntityMethod();

        if(
            $currentTargetEntity !== $targetEntity
            OR
            ($currentTargetEntity && $currentTargetEntity->getDeletedAt() !== null)
            OR
            ($targetEntity && $targetEntity->getDeletedAt() !== null)
        ){
            $setTargetEntityMethod = $methods['setEntity'];
            $entity->$setTargetEntityMethod($targetEntity);

            $resultBag->addChanged($entity);
        }else{
            $resultBag->addSkipped($entity);
        }
    }
}
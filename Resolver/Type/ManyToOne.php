<?php

namespace Ibrows\AssociationResolver\Resolver\Type;

use Ibrows\AssociationResolver\Result\ResultBag;
use Ibrows\AssociationResolver\Reader\AssociationMappingInfoInterface;

use Symfony\Component\Console\Output\OutputInterface;

class ManyToOne extends AbstractResolver
{
    /**
     * @param ResultBag $resultBag
     * @param AssociationMappingInfoInterface $mappingInfo
     * @param string $propertyName
     * @param mixed $entity
     * @param OutputInterface $output
     * @return void
     */
    public function resolveAssociation(
        ResultBag $resultBag,
        AssociationMappingInfoInterface $mappingInfo,
        $propertyName,
        $entity,
        OutputInterface $output
    ){
        $manyToOne = $mappingInfo->getAnnotation();
        $metaData = $mappingInfo->getMetaData();

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

        $softDeletableGetter = $this->getSoftdeletableGetter();

        if(($currentTargetEntity !== $targetEntity) ||
           ($this->isSoftdeletable() && $currentTargetEntity && $currentTargetEntity->$softDeletableGetter() !== null) ||
           ($this->isSoftdeletable() && $targetEntity && $targetEntity->$softDeletableGetter() !== null)
        ) {
            $setTargetEntityMethod = $methods['setEntity'];
            $entity->$setTargetEntityMethod($targetEntity);

            $resultBag->addChanged($entity);
        }else{
            $resultBag->addSkipped($entity);
        }
    }
}
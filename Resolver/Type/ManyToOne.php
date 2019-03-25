<?php

namespace Ibrows\AssociationResolver\Resolver\Type;

use Doctrine\ORM\QueryBuilder;
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
    ) {
        $manyToOne = $mappingInfo->getAnnotation();
        $metaData = $mappingInfo->getMetaData();

        $getSetMethods = array(
            'setEntity' => $manyToOne->getEntitySetterName() ?: 'set'. ucfirst($propertyName),
            'getEntity' => $manyToOne->getEntityGetterName() ?: 'get'. ucfirst($propertyName),
        );


        $this->checkIfMethodsExists($getSetMethods, $entity);

        $criterias = $this->getCriterias($resultBag, $mappingInfo, $propertyName, $entity, $output);

        $targetClassRepo = $this->entityManager->getRepository($metaData['targetEntity']);
        $targetEntity = $targetClassRepo->findOneBy($criterias);

        $getCurrentTargetEntityMethod = $getSetMethods['getEntity'];
        $currentTargetEntity = $entity->$getCurrentTargetEntityMethod();

        $softDeletableGetter = $this->getSoftdeletableGetter();

        if (($currentTargetEntity !== $targetEntity) ||
            ($this->isSoftdeletable() && $currentTargetEntity && $currentTargetEntity->$softDeletableGetter() !== null) ||
            ($this->isSoftdeletable() && $targetEntity && $targetEntity->$softDeletableGetter() !== null)
        ) {
            $setTargetEntityMethod = $getSetMethods['setEntity'];
            $entity->$setTargetEntityMethod($targetEntity);

            $resultBag->addChanged($entity);
        } else {
            $resultBag->addSkipped($entity);
        }
    }

    /**
     * @param ResultBag $resultBag
     * @param QueryBuilder $qb
     * @param AssociationMappingInfoInterface $mappingInfo
     * @param $propertyName
     * @param $className
     */
    public function prepareQB(ResultBag $resultBag, QueryBuilder $qb, AssociationMappingInfoInterface $mappingInfo, $propertyName, $className)
    {
        $alias = $qb->getRootAliases()[0];
        $annotation = $mappingInfo->getAnnotation();
        $metaData = $mappingInfo->getMetaData();
        if (!$annotation->getValueFieldName()) {
            return;
        }
        /** @var $qb QueryBuilder */
        $subSelect = "select t.".'id from ' .$metaData['targetEntity'].' as t WHERE '.$alias.'.'.$annotation->getValueFieldName().' = t.'.$annotation->getTargetFieldName();
        $qb->andWhere("$alias.$propertyName IS NULL OR $alias.$propertyName != ($subSelect)");
    }


    /**
     * @param ResultBag $resultBag
     * @param AssociationMappingInfoInterface $mappingInfo
     * @param string $propertyName
     * @param mixed $entity
     * @param OutputInterface $output
     * @return array
     */
    protected function getCriterias(
        ResultBag $resultBag,
        AssociationMappingInfoInterface $mappingInfo,
        $propertyName,
        $entity,
        OutputInterface $output
    ) {
        /** @var \Ibrows\AssociationResolver\Annotation\ManyToOne $manyToOne */
        $manyToOne = $mappingInfo->getAnnotation();

        $valueFieldMethods = array(
            'getValue' => $manyToOne->getValueGetterName() ?: 'get'. ucfirst($manyToOne->getValueFieldName())
        );

        $this->checkIfMethodsExists($valueFieldMethods, $entity);

        $getSearchFieldValueMethod = $valueFieldMethods['getValue'];

        return array(
            $manyToOne->getTargetFieldName() => $entity->$getSearchFieldValueMethod()
        );
    }
}

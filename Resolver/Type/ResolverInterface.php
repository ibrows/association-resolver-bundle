<?php

namespace Ibrows\AssociationResolver\Resolver\Type;

use Doctrine\ORM\QueryBuilder;
use Ibrows\AssociationResolver\Result\ResultBag;
use Ibrows\AssociationResolver\Reader\AssociationMappingInfoInterface;

use Symfony\Component\Console\Output\OutputInterface;

interface ResolverInterface
{
    /**
     * @param ResultBag $resultBag
     * @param AssociationMappingInfoInterface $mappingInfo
     * @param string $propertyName
     * @param mixed $entity
     * @param OutputInterface $output
     * @return ResolverInterface
     */
    public function resolveAssociation(
        ResultBag $resultBag,
        AssociationMappingInfoInterface $mappingInfo,
        $propertyName,
        $entity,
        OutputInterface $output
    );

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
    );


    /**
     * @param ResultBag $resultBag
     * @param QueryBuilder $qb
     * @param AssociationMappingInfoInterface $mappingInfo
     * @param $propertyName
     * @param $className
     */
    public function prepareQB(ResultBag $resultBag, QueryBuilder $qb, AssociationMappingInfoInterface $mappingInfo,$propertyName,$className)  ;
}
<?php

namespace Ibrows\AssociationResolver\Resolver\Type;

use Ibrows\AssociationResolver\Result\ResultBag;
use Ibrows\AssociationResolver\Reader\AssociationMappingInformationInterface;

use Doctrine\ORM\EntityManager;

interface ResolverInterface
{
    public function setEntityManager(EntityManager $entityManager);

    /**
     * @param ResultBag $resultBag
     * @param AssociationMappingInformationInterface $mappingInformation
     * @param string $propertyName
     * @param mixed $entity
     */
    public function resolveAssociation(
        ResultBag $resultBag,
        AssociationMappingInformationInterface $mappingInformation,
        $propertyName,
        $entity
    );
}
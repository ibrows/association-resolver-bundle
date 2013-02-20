<?php

namespace Ibrows\AssociationResolver\Resolver;

use Ibrows\AssociationResolver\Resolver\Type\ResolverInterface as ResolverTypeInterface;
use Ibrows\AssociationResolver\Reader\AssociationAnnotationReaderInterface;
use Ibrows\AssociationResolver\Result\ResultBag;
use Ibrows\AssociationResolver\Reader\AssociationMappingInformationInterface;

use Doctrine\ORM\EntityManager;

class Resolver implements ResolverInterface
{
    /**
     * @var AssociationAnnotationReaderInterface
     */
    protected $annotationReader;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var ResultBag
     */
    protected $resultBag = null;

    /**
     * @param AssociationAnnotationReaderInterface $annotationReader
     * @return Resolver
     */
    public function setAnnotationReader(AssociationAnnotationReaderInterface $annotationReader)
    {
        $this->annotationReader = $annotationReader;
        return $this;
    }

    /**
     * @param EntityManager $entityManager
     * @return Resolver
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @param ResultBag $resultBag
     * @return Resolver
     */
    public function setResultBag(ResultBag $resultBag = null)
    {
        $this->resultBag = $resultBag;
        return $this;
    }

    /**
     * @return ResultBag
     */
    public function getResultBag()
    {
        if(null !== $this->resultBag){
            return $this->resultBag;
        }
        return $this->resultBag = new ResultBag();
    }

    /**
     * @param string $className
     */
    public function resolveAssociations($className)
    {
        $resultBag = $this->getResultBag();

        $associationAnnotations = $this->annotationReader->getAssociationAnnotations($className);

        $entities = $this->entityManager->getRepository($className)->findAll();

        foreach($entities as $entity){
            foreach($associationAnnotations as $propertyName => $mappingInformation){
                $resolver = $this->getTypeResolver($mappingInformation);
                $resolver->resolveAssociation($resultBag, $mappingInformation, $propertyName, $entity);
            }
        }

        $resultBag->setCountProcessed(count($entities));
    }

    /**
     * @param AssociationMappingInformationInterface $mappingInformation
     * @return ResolverTypeInterface
     */
    protected function getTypeResolver(AssociationMappingInformationInterface $mappingInformation)
    {
        $associationResolverClassName =
            'Ibrows\\AssociationResolver\\Resolver\\Type\\'. $mappingInformation->getAnnotation()->getType();

        $resolver = new $associationResolverClassName();
        $resolver->setEntityManager($this->entityManager);

        return $resolver;
    }
}
<?php

namespace Ibrows\AssociationResolver\Resolver;

use Ibrows\AssociationResolver\Resolver\Type\ResolverInterface as ResolverTypeInterface;

use Ibrows\AssociationResolver\Reader\AssociationMappingInfoInterface;
use Ibrows\AssociationResolver\Result\ResultBag;

use Ibrows\AssociationResolver\Exception\ResolverNotFoundException;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Console\Output\OutputInterface;

class ResolverChain implements ResolverChainInterface
{
    /**
     * @var ResolverTypeInterface[]|Collection[]
     */
    protected $resolvers;

    public function __construct()
    {
        $this->resolvers = new ArrayCollection();
    }
    /**
     * @param ResolverTypeInterface $resolver
     * @return ResolverChainInterface
     */
    public function addResolver(ResolverTypeInterface $resolver)
    {
        $this->resolvers->add($resolver);
        return $this;
    }

    /**
     * @param ResolverTypeInterface $resolver
     * @return ResolverChainInterface
     */
    public function removeResolver(ResolverTypeInterface $resolver)
    {
        $this->resolvers->removeElement($resolver);
        return $this;
    }

    /**
     * @return ResolverTypeInterface[]|Collection
     */
    public function getResolvers()
    {
        return $this->resolvers;
    }

    /**
     * @param ResultBag $resultBag
     * @param AssociationMappingInfoInterface $mappingInfo
     * @param string $propertyName
     * @param mixed $entity
     * @param OutputInterface $output
     * @return ResolverChain
     * @throws ResolverNotFoundException
     */
    public function resolveAssociation(
        ResultBag $resultBag,
        AssociationMappingInfoInterface $mappingInfo,
        $propertyName,
        $entity,
        OutputInterface $output
    ){
        $resolver = $this->getResponsibleResolver($resultBag, $mappingInfo, $propertyName, $entity);

        if(!$resolver){
            throw new ResolverNotFoundException(sprintf(
                'No responsible resolver found for annotation "%s", entity "%s" and property "%s"',
                get_class($mappingInfo->getAnnotation()),
                get_class($entity),
                $propertyName
            ));
        }

        $resolver->resolveAssociation($resultBag, $mappingInfo, $propertyName, $entity, $output);

        return $this;
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
        return null !== $this->getResponsibleResolver($resultBag, $mappingInfo, $propertyName, $entity);
    }

    /**
     * @param ResultBag $resultBag
     * @param AssociationMappingInfoInterface $mappingInfo
     * @param string $propertyName
     * @param mixed $entity
     * @return ResolverTypeInterface
     */
    protected function getResponsibleResolver(
        ResultBag $resultBag,
        AssociationMappingInfoInterface $mappingInfo,
        $propertyName,
        $entity
    ){
        foreach($this->getResolvers() as $resolver){
            if($resolver->isResponsible($resultBag, $mappingInfo, $propertyName, $entity)){
                return $resolver;
            }
        }
        return null;
    }
}
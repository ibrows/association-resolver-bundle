<?php

namespace Ibrows\AssociationResolver\Resolver;

use Ibrows\AssociationResolver\Result\ResultBag;

use Ibrows\AssociationResolver\Reader\AssociationAnnotationReaderInterface;
use Ibrows\AssociationResolver\Reader\AssociationMappingInfoInterface;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\NullOutput;

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
     * @var ResolverChain
     */
    protected $resolverChain;

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
     * @param ResolverChainInterface $resolverChain
     * @return ResolverInterface
     */
    public function setResolverChain(ResolverChainInterface $resolverChain)
    {
        $this->resolverChain = $resolverChain;
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
     * @param OutputInterface $output
     * @param bool $flush
     * @return Resolver
     */
    public function resolveAssociations($className, OutputInterface $output = null, $flush = false)
    {
        if(null === $output){
            $output = new NullOutput();
        }

        $output->writeln('<comment>---Start Resolve Associations---</comment>');

        $resultBag = $this->getResultBag();
        $associationAnnotations = $this->annotationReader->getAssociationAnnotations($className);
        $entities = $this->entityManager->getRepository($className)->findAll();

        foreach($entities as $entity){
            foreach($associationAnnotations as $propertyName => $mappingInfo){
                $this->resolverChain->resolveAssociation($resultBag, $mappingInfo, $propertyName, $entity, $output);
            }
        }

        $resultBag->setCountProcessed(count($entities));
        $this->writeResultBagToOutput($output, $resultBag);

        if(true === $flush){
            $em = $this->entityManager;
            foreach($resultBag->getChanged() as $entity){
                $em->persist($entity);
            }
            $em->flush();
        }

        return $this;
    }

    /**
     * @param OutputInterface $output
     * @param ResultBag $resultBag
     */
    protected function writeResultBagToOutput(OutputInterface $output, ResultBag $resultBag)
    {
        $output->writeln('<comment>Processed: ' . $resultBag->countProcessed() . ' Entries</comment>');
        $output->writeln('<info>Changed: ' . $resultBag->countChanged() . ' Entries</info>');
        $output->writeln('<info>Skipped: ' . $resultBag->countSkipped() . ' Entries</info>');
        $output->writeln('<error>New: ' . $resultBag->countNew() . ' Entries</error>');
    }
}
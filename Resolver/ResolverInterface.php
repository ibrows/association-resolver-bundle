<?php

namespace Ibrows\AssociationResolver\Resolver;

use Ibrows\AssociationResolver\Result\ResultBag;
use Ibrows\AssociationResolver\Reader\AssociationAnnotationReaderInterface;

interface ResolverInterface
{
    /**
     * @param AssociationAnnotationReaderInterface $annotationReader
     * @return ResolverInterface
     */
    public function setAnnotationReader(AssociationAnnotationReaderInterface $annotationReader);

    /**
     * @param ResultBag $resultBag
     * @return mixed
     */
    public function setResultBag(ResultBag $resultBag = null);

    /**
     * @return ResultBag
     */
    public function getResultBag();

    /**
     * @param string $className
     * @return void
     */
    public function resolveAssociations($className);
}
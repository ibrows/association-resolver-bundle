<?php

namespace Ibrows\AssociationResolver\Result;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ResultBag
{
    /**
     * @var array
     */
    protected $changed = array();

    /**
     * @var array
     */
    protected $new = array();

    /**
     * @var array
     */
    protected $skipped = array();

    /**
     * @var Collection
     */
    protected $removing;

    public function __construct()
    {
        $this->removing = new ArrayCollection();
    }

    /**
     * @param mixed $entity
     */
    public function unsetRemoving($entity)
    {
        $this->removing->removeElement($entity);
    }

    /**
     * @param mixed $entity
     * @return ResultBag
     */
    public function addSkipped($entity = null)
    {
        $this->skipped[] = $entity;
        $this->unsetRemoving($entity);

        return $this;
    }

    /**
     * @return array
     */
    public function getSkipped()
    {
        return $this->skipped;
    }

    /**
     * @param Collection $removing
     * @return ResultBag
     */
    public function setRemoving(Collection $removing)
    {
        $this->removing = $removing;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRemoving()
    {
        return $this->removing;
    }

    /**
     * @return int
     */
    public function countRemoving()
    {
        return count($this->removing);
    }

    /**
     * @return int
     */
    public function countSkipped()
    {
        return count($this->skipped);
    }

    /**
     * @param mixed $entity
     * @return ResultBag
     */
    public function addChanged($entity)
    {
        $this->changed[] = $entity;
        $this->unsetRemoving($entity);

        return $this;
    }

    /**
     * @param int $processed
     * @return ResultBag
     */
    public function setCountProcessed($processed)
    {
        $this->processed = (int)$processed;
        return $this;
    }

    /**
     * @return int
     */
    public function countProcessed()
    {
        return $this->processed;
    }

    /**
     * @param mixed $entity
     * @return ResultBag
     */
    public function addNew($entity)
    {
        $this->new[] = $entity;

        return $this;
    }

    /**
     * @return array
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * @return array
     */
    public function getChanged()
    {
        return $this->changed;
    }

    /**
     * @return int
     */
    public function countNew()
    {
        return count($this->new);
    }

    /**
     * @return int
     */
    public function countChanged()
    {
        return count($this->changed);
    }

    /**
     * @return bool
     */
    public function hasChanges()
    {
        return $this->countChanged() > 0 || $this->countNew() > 0;
    }
}
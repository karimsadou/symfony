<?php

namespace kapla\page\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Content
 *
 * @ORM\Table(name="content")
 * @ORM\Entity(repositoryClass="kapla\page\Repository\ContentRepository")
 */
class Content
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * One Content has Many Blocs
     * @ORM\OneToMany(targetEntity="kapla\page\Entity\Bloc", mappedBy="content")
     */
    private $blocs;
    /**
     * Clone
     */
    public function __clone()
    {
        if ($this->id) {
            $this->setId(null);
        }
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Content
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->blocs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add bloc.
     *
     * @param \kapla\page\Entity\Bloc $bloc
     *
     * @return Content
     */
    public function addBloc(\kapla\page\Entity\Bloc $bloc)
    {
        $this->blocs[] = $bloc;

        return $this;
    }

    /**
     * Remove bloc.
     *
     * @param \kapla\page\Entity\Bloc $bloc
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBloc(\kapla\page\Entity\Bloc $bloc)
    {
        return $this->blocs->removeElement($bloc);
    }

    /**
     * Get blocs.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlocs()
    {
        return $this->blocs;
    }

    /**
     * @param mixed $blocs
     */
    public function setBlocs($blocs)
    {
        $this->blocs = $blocs;
    }

}

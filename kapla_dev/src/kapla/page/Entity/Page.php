<?php

namespace kapla\page\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="kapla\page\Repository\PageRepository")
 */
class Page
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"}, updatable=true, unique=true)
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="kapla\page\Entity\Bloc", mappedBy="page", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"num_order" = "ASC"})
     */
    private $Blocs;


    /**
     * Many Pages have Many Category
     * @ORM\ManyToMany(targetEntity="kapla\page\Entity\Category", inversedBy="pages")
     * 
     */
    private $category;

    /**
     *
     * @ORM\OneToOne(targetEntity="kapla\upload\Entity\Files", cascade={"all"})
     */
    private $upfile;


    /**
     * @return mixed
     */
    public function getUpfile()
    {
        return $this->upfile;
    }

    /**
     * @param mixed $upfile
     */
    public function setUpfile($upfile)
    {
        $this->upfile = $upfile;
    }

    /**
     * Get id
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
     * Set titre
     *
     * @param string $title
     *
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Page
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->Blocs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cateogry = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Add Bloc.
     *
     * @param \kapla\page\Entity\Bloc $Bloc
     *
     * @return Page
     */
    public function addBloc(\kapla\page\Entity\Bloc $Bloc)
    {
        $this->Blocs[] = $Bloc;
        $Bloc->setPage($this);

        return $this;
    }

    /**
     * Remove Bloc.
     *
     * @param \kapla\page\Entity\Bloc $Bloc
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBloc(\kapla\page\Entity\Bloc $Bloc)
    {
        return $this->Blocs->removeElement($Bloc);
    }

    /**
     * Get Blocs.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlocs()
    {
        return $this->Blocs;
    }

    /**
     * Add category.
     *
     * @param \kapla\page\Entity\Category $category
     *
     * @return Page
     */
    public function addCategory(\kapla\page\Entity\Category $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category.
     *
     * @param \kapla\page\Entity\Category $category
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCategory(\kapla\page\Entity\Category $category)
    {
        return $this->category->removeElement($category);
    }

    /**
     * Get category.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategory()
    {
        return $this->category;
    }
}

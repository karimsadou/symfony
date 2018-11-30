<?php

namespace kapla\page\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="kapla\page\Repository\CategoryRepository")
 * @UniqueEntity(
                fields={"name"},
                message="Nom déjà utilisé."
    )
 */
class Category
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * One Parent Category has Many Children Categories.
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"num_order" = "ASC"})
     */
    private $children;

    /**
     * Many Categories have One Parent Category.
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * Many Category has many pages
     * @ORM\ManyToMany(targetEntity="kapla\page\Entity\Page", mappedBy="category")
     */
    private $pages;

    /**
     * Une category a une page principale
     * @ORM\ManyToOne(targetEntity="kapla\page\Entity\Page")
     * @ORM\JoinColumn(name="mainpage_id", referencedColumnName="id")
     */
    private $mainpage;

    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     * @ORM\Column(name="num_order", type="integer", nullable=true)
     */
    private $num_order;

    /**
     * Set numOrder
     *
     * @param $numOrder
     *
     * @return Bloc
     */
    public function setNumOrder($numOrder)
    {
        $this->num_order = $numOrder;

        return $this;
    }

    /**
     * Get numOrder
     *
     * @return \int
     */
    public function getNumOrder()
    {
        return $this->num_order;
    }

    /**
     * @return mixed
     */
    public function getMainpage()
    {
        return $this->mainpage;
    }

    /**
     * @param mixed $mainpage
     */
    public function setMainpage($mainpage)
    {
        $this->mainpage = $mainpage;
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
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Category
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
     * Add child
     *
     * @param \kapla\page\Entity\Category $child
     *
     * @return Category
     */
    public function addChild(\kapla\page\Entity\Category $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \kapla\page\Entity\Category $child
     */
    public function removeChild(\kapla\page\Entity\Category $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \kapla\page\Entity\Category $parent
     *
     * @return Category
     */
    public function setParent(\kapla\page\Entity\Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \kapla\page\Entity\Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Add page.
     *
     * @param \kapla\page\Entity\Page $page
     *
     * @return Category
     */
    public function addPage(\kapla\page\Entity\Page $page)
    {
        $this->pages[] = $page;

        return $this;
    }

    /**
     * Remove page.
     *
     * @param \kapla\page\Entity\Page $page
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePage(\kapla\page\Entity\Page $page)
    {
        return $this->pages->removeElement($page);
    }

    /**
     * Get pages.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPages()
    {
        return $this->pages;
    }
}

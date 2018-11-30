<?php

namespace kapla\page\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Bloc
 *
 * @ORM\Table(name="bloc")
 * @ORM\Entity(repositoryClass="kapla\page\Repository\BlocRepository")
 */
class Bloc
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
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * Many blocs have one content
     * @ORM\ManyToOne(targetEntity="kapla\page\Entity\Content", inversedBy="blocs", cascade={"persist"})
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="kapla\page\Entity\Page", inversedBy="Blocs")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $page;

    /**
     * @var int
     *
     * @ORM\Column(name="typeBloc", type="integer", nullable=false)
     * 
     */
    private $typeBloc;

    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     * @ORM\Column(name="num_order", type="integer", nullable=true)
     */
    private $num_order;

    /**
     * @ORM\Column(name="published", type="boolean", options={"default": 1})
     */
    private $published = 1;

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set published
     *
     * @param boolean $_published
     *
     * @return Bloc
     */
    public function setPublished($_published)
    {
        $this->published = $_published;

        return $this;
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
     * Set id
     *
     *  @param int
     *
     *  @return Bloc
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Bloc
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


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
     * Set typeBloc
     *
     * @param $typeBloc
     *
     * @return Bloc
     */
    public function setTypeBloc($type)
    {
        $this->typeBloc = $type;

        return $this;
    }

    /**
     * Get typeBloc
     *
     * @return int
     */
    public function getTypeBloc()
    {
        return $this->typeBloc;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }


}

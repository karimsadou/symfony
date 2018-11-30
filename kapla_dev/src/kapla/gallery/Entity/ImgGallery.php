<?php

namespace kapla\gallery\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * ImgGallery
 *
 * @ORM\Table(name="img_gallery")
 * @ORM\Entity(repositoryClass="kapla\gallery\Repository\ImgGalleryRepository")
 */
class ImgGallery
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
     * @ORM\Column(name="caption", type="string", length=255, nullable=true)
     */
    private $caption;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var int
     *
     * @ORM\Column(name="num_order", type="integer")
     */
    private $numOrder;

    /** One to One (surcharge Image)
     * @ORM\OneToOne(targetEntity="kapla\upload\Entity\Files", cascade={"all"})
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    private $image;

    /**
     * Many img gallery for one gallery
     * @ORM\ManyToOne(targetEntity="kapla\gallery\Entity\Gallery", inversedBy="images")
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $gallery;
    /**
     * Clone
     */
    public function __clone()
    {
        if ($this->id) {
            $this->setId(null);
            $image = $this->getImage();

            /** @var File $imageClone*/
            $imageClone = clone $image;

            $this->image = $imageClone;

        }
    }




    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * Set caption.
     *
     * @param string $caption
     *
     * @return ImgGallery
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption.
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set numOrder.
     *
     * @param int $numOrder
     *
     * @return ImgGallery
     */
    public function setNumOrder($numOrder)
    {
        $this->numOrder = $numOrder;

        return $this;
    }

    /**
     * Get numOrder.
     *
     * @return int
     */
    public function getNumOrder()
    {
        return $this->numOrder;
    }

    /**
     * Set image.
     *
     * @param \kapla\upload\Entity\Files|null $image
     *
     * @return ImgGallery
     */
    public function setImage(\kapla\upload\Entity\Files $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return \kapla\upload\Entity\Files|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set gallery.
     *
     * @param \kapla\gallery\Entity\Gallery|null $gallery
     *
     * @return ImgGallery
     */
    public function setGallery(\kapla\gallery\Entity\Gallery $gallery = null)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get gallery.
     *
     * @return \kapla\gallery\Entity\Gallery|null
     */
    public function getGallery()
    {
        return $this->gallery;
    }


    /**
     * Set url.
     *
     * @param string|null $url
     *
     * @return ImgGallery
     */
    public function setUrl($url = null)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }


}

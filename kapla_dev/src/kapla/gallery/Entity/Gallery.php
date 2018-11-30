<?php

namespace kapla\gallery\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Gallery
 *
 * @ORM\Table(name="gallery")
 * @ORM\Entity(repositoryClass="kapla\gallery\Repository\GalleryRepository")
 */
class Gallery
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
     * @ORM\Column(name="titre", type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * One gallery has many images
     * @ORM\OneToMany(targetEntity="kapla\gallery\Entity\ImgGallery", mappedBy="gallery", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"numOrder" = "ASC"})
     */
    private $images;


    /**
     * @var string
     *
     * @ORM\Column(name="theme", type="string", length=255, nullable=true)
     */
    private $theme;

    /**
     * @ORM\OneToOne(targetEntity="kapla\page\Entity\Content" , cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $content;

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


    /**
     * Clone
     */
     public function __clone()
    {
        if ($this->id) {
            // Supprimer la référence orm à la base de données.
            $this->setId(null);
            // Collectez les images à cloner de sorte que toute modification ultérieure des images dans
            // l'entité principale n'affecte pas l'entité clonée.
            $images = $this->getImages();
            $content = $this->getContent();
            $imagesArray = new ArrayCollection();
            //$blocsArray = new ArrayCollection();


                /** @var Content $contentClone*/
                $contentClone = clone $content;

                if ($images) {
                    $i=0;
                    foreach ($images as $image) {
                        /** @var ImgGallery $imageClone*/
                        $imageClone = clone $image;

                        $imageClone->setGallery($this);
                        $imageClone->setId($i++);
                        $imagesArray->add($imageClone);
                    }

                    $this->images = $imagesArray;

                }else{
                    echo 'not an array <br/>';
                }
                 $this->content = $contentClone;


        }
    }



    public function cloneCourses($courseIds, $newSemester) {
        $courses = $this->getRepository()->findByIds($courseIds);
        foreach($courses as $course) {
            $newCourse = clone $course;
            $newCourse->setSemester($newSemester);
            $this->em->persist($newCourse);
            $this->em->flush();

            foreach($course->getTeachers() as $teacher) {
                $newCourse->addTeacher($teacher);
            }
            $this->em->persist($newCourse);
            $this->em->flush();
        }
    }





    /**
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param string $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
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
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add image.
     *
     * @param \kapla\gallery\Entity\ImgGallery $image
     *
     * @return Gallery
     */
    public function addImage(\kapla\gallery\Entity\ImgGallery $image)
    {
        $this->images[] = $image;
        $image->setGallery($this);

        return $this;
    }

    /**
     * Remove image.
     *
     * @param \kapla\gallery\Entity\ImgGallery $image
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeImage(\kapla\gallery\Entity\ImgGallery $image)
    {
        return $this->images->removeElement($image);
    }

    /**
     * Get images.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

}

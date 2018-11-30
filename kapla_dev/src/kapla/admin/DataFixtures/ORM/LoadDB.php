<?php

namespace kapla\admin\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use kapla\gallery\Entity\Gallery;
use kapla\page\Entity\Content;
use kapla\page\Entity\Page;
use kapla\page\Entity\Bloc;
use kapla\page\Entity\Category;
use kapla\pagelist\Entity\PageList;
use kapla\richtext\Entity\RichText;
use kapla\upload\Entity\Files;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use kapla\video\Entity\Video;


class LoadDB extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{
	/**
     * @var ContainerInterface
     */
	private $container;

	/**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
   	}

	/**
     * {@inheritDoc}
     */
  	public function load(ObjectManager $manager)
  	{
  		//
  		// Setting Admin User
  		//
  		/** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
  		$userManager = $this->container->get('fos_user.user_manager');
    	$user = $userManager->createUser();
    	$user->setUsername('admin');
    	$user->setEmail('email@domain.com');
    	$user->setPlainPassword('root');
    	$user->setEnabled(true);
    	$user->setRoles(array('ROLE_ADMIN'));
    	$userManager->updateUser($user, true);
    	//
    	// END
    	//

    	//
    	// Setting Our Data For test DB
    	//
    	$file = new Files();
    	$file->setImage("fakeImage.jpeg");

    	$contentR = new Content();
    	$contentR->setId(1);
    	$contentR->setType("RichText");

        $contentG = new Content();
        $contentG->setId(2);
        $contentG->setType("Gallery");

        $contentV = new Content();
        $contentV->setId(3);
        $contentV->setType("Video");

        $contentP = new Content();
        $contentP->setId(4);
        $contentP->setType("PageList");



        $richText = new RichText();
        $richText->setId(1);
        $richText->setContent($contentR);

        $gallery = new Gallery();
        $gallery->setId(1);
        $gallery->setContent($contentG);

        $video = new Video();
        $video->setId(1);
        $video->setContent($contentV);

        $pageList = new PageList();
        $pageList->setId(1);
        $pageList->setContent($contentP);


    	$blocGallery = new Bloc();
    	$blocGallery->setTitle("bloc galerie");
    	$blocGallery->setTypeBloc(1);
    	$blocGallery->setNumOrder(2);
    	$blocGallery->setContent($contentG);
    	$blocGallery->setPublished(true);
    	$blocGallery->setId(2);

		$blocPageList = new Bloc();
    	$blocPageList->setTitle("bloc mosaïque de pages");
    	$blocPageList->setTypeBloc(1);
    	$blocPageList->setNumOrder(0);
    	$blocPageList->setContent($contentP);
    	$blocPageList->setPublished(true);
    	$blocPageList->setId(3);

    	$blocVideo = new Bloc();
    	$blocVideo->setTitle("bloc video");
    	$blocVideo->setTypeBloc(1);
    	$blocVideo->setNumOrder(0);
    	$blocVideo->setContent($contentV);
    	$blocVideo->setPublished(true);
    	$blocVideo->setId(4);

        $bloc = new Bloc();
        $bloc->setTitle("bloc title");
        $bloc->setTypeBloc(1);
        $bloc->setNumOrder(0);
        $bloc->setContent($contentR);
        $bloc->setPublished(true);
        $bloc->setId(5);

    	
    	$page = new Page();
    	$page->setId(1);
    	$page->setUpfile($file);
    	$page->setTitle("toto");
    	$page->addBloc($bloc);
    	$page->addBloc($blocGallery);
    	$page->addBloc($blocPageList);
    	$page->addBloc($blocVideo);

    	$category = new Category();
    	$category->setName('Accueil');
    	$category->setMainpage($page);
    	$category->setNumOrder(0);

		

    	$manager->persist($page);
    	$manager->persist($category);
    	$manager->flush();
    	//
    	// END
    	//
  	}
}
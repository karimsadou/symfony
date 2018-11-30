<?php

namespace kapla\gallery\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use kapla\gallery\Entity\Gallery;
use kapla\page\Entity\Content;
use kapla\gallery\Form\GalleryType;
use kapla\page\Entity\Bloc;
use kapla\admin\Menu\Breadcrumb;


class GalleryController extends Controller
{

    /**
     * @Route("/admin/{slug}/gallery/edit/{id}", name="GalleryBundle_edit")
     */
    public function editAction(Request $request, Bloc $bloc, Content $content ,$id, $slug)
    {
        //
        // CREATION DU FIL D'ARIANE
        //
        $slugPage = $slug;
        $path = $request->getPathInfo();
        $slug = $bloc->getTitle();
        $breadcrumb= new Breadcrumb();
        $bc = $breadcrumb->getBreadcrumb($path, 0, $id, $slugPage);
        //
        // END CREATION DU FIL D'ARIANE
        //



        $theme = $this->getParameter("gallery_theme");
        $em = $this->getDoctrine()->getManager();
        $gallery = $em->getRepository('GalleryBundle:Gallery')->findOneBy(array('content' => $bloc->getContent()));
        //dump($content); die;
        if (!$gallery) {
            $gallery = new Gallery;
        }

        $imggallery = $gallery->getImages();
        $tab2 = [];
        foreach ($imggallery as $img)
        {
            $tab2[$img->getImage()->getId()] = $img->getImage()->getImage();
        }


        $form = $this->createForm(GalleryType::class, $gallery, array("gallery_theme" => $theme));
        $form->handleRequest($request);

        //dump( $gallery);
        $i=0;
        $galleryCloned = clone $gallery;
        $galleryCloned->setId($i++);
        //dump( $galleryCloned);die;

        if ($form->isValid()) {
            $updatePreviewButton = $form->get('updatePreview');
            $editButton = $form->get('edit');
            $editPreviewButton = $form->get('editPreview');
            $retourButton = $form->get('retour');

            if ($editButton->isClicked() || $editPreviewButton->isClicked()) {

                foreach ($gallery->getImages() as $imggallery) {
                    if ($imggallery->getImage()->getImage() == null) {
                        $id = $imggallery->getImage()->getId();
                        if (array_key_exists($id, $tab2)) {
                            $imggallery->getImage()->setImage($tab2[$id]);
                        }

                    }
                }

                $gallery->setContent($bloc->getContent());

                $em = $this->getDoctrine()->getManager();
                $em->persist($gallery);
                $em->flush();

                if ($bloc->getPage() === null) {
                    return $this->redirectToRoute('admin_content_listing', array("bloc" => $bloc));
                }

                return $this->redirectToRoute('admin_page_show', array("slug" => $bloc->getPage()->getSlug()));

            }else if ( $updatePreviewButton ->isClicked()){

                foreach ($gallery->getImages() as $imggallery) {
                    if ($imggallery->getImage()->getImage() == null) {
                        $id = $imggallery->getImage()->getId();
                        if (array_key_exists($id, $tab2)) {
                            $imggallery->getImage()->setImage($tab2[$id]);
                        }
                     }
                }

                $gallery->setContent($bloc->getContent());

                //$form = $this->createForm(GalleryType::class, $gallery, array("gallery_theme" => $theme));
                return $this->render('GalleryBundle:Default:new.html.twig', array(
                    'form' => $form->createView(),
                    'tab' => $tab2,
                    'gallery' => $gallery,
                    'breadcrumb' => $bc,
                    'slug' => $slug,
                    'id' => $bloc->getId(),
                    'slugPage' => $slugPage
                ));
                
            }else if ( $retourButton->isClicked()){

                $gallery->setContent($bloc->getContent());
                $em = $this->getDoctrine()->getManager();

                $em->persist($gallery);
                $em->flush();

               // $form = $this->createForm(GalleryType::class, $gallery, array("gallery_theme" => $theme));
                return $this->render('GalleryBundle:Default:new.html.twig', array(
                    'form' => $form->createView(),
                    'tab' => $tab2,
                    'gallery' => $gallery,
                    'breadcrumb' => $bc,
                    'slug' => $slug,
                    'id' => $bloc->getId(),
                    'slugPage' => $slugPage
                ));
            }
        }

        return $this->render('GalleryBundle:Default:new.html.twig', array(
            'form' => $form->createView(),
            'tab' => $tab2,
            'gallery' => $gallery,
            'breadcrumb' => $bc,
            'slug' => $slug,
            'id' => $bloc->getId(),
            'slugPage' => $slugPage
        ));



    }

    /**
     * @Route("/gallery/{id}", name="GalleryBundle_show")
     */
    public function showAction(Gallery $gallery)
    {
        return $this->render('GalleryBundle:Default:show.html.twig', array(
            'gallery' => $gallery,
        ));
    }
}

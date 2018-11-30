<?php

namespace kapla\admin\Controller;

use kapla\page\Entity\Bloc;
use kapla\page\Form\MultiBlocsType;
use kapla\page\Form\MultiBlocType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use kapla\page\Entity\Page;
use kapla\page\Entity\Content;
use kapla\page\Form\PageType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use kapla\upload\Form\FilesType;
use kapla\upload\Entity\Files;
use Symfony\Component\HttpFoundation\Request;
use kapla\admin\Menu\Breadcrumb;



class AdminPageController extends Controller
{
    /**
     * @Route("/", name="admin_dashboard")
     */
    public function indexAction(Request $request)
    {
        //
        // CREATION DU FIL D'ARIANE
        //
        $path = $request->getPathInfo();
        $breadcrumb= new Breadcrumb();
        $bc = $breadcrumb->getBreadcrumb($path);
        //
        // END CREATION DU FIL D'ARIANE
        //
        $mode = file_get_contents(__DIR__.'/../Resources/config/maintenance.lock');
        $em = $this->getDoctrine()->getManager();
        $bundles = $this->container->getParameter('kernel.bundles');
        $users = null;
        $listeUsers = null;
        foreach ($bundles as $myBundle) {
            if ($myBundle == 'kapla\formation\FormationBundle')
                $users = $em->getRepository('FormationBundle:Registration')->findBy(array(), array('registration_date' => 'DESC'));
            elseif ($myBundle == 'kapla\ProgrammeBundle\ProgrammeBundle')
                $users = $em->getRepository('ProgrammeBundle:Participant')->findBy(array(), array('lastname' => 'asc'));
            }
        if ($users == null)
            return $this->render('AdminBundle:Page:adminDashboard.html.twig', array(
                'mode' => $mode,
                'breadcrumb' => $bc
            ));
        else
        {
            $listeUsers = $this->get('knp_paginator')->paginate($users, $request->query->get('page', 1), 6);

            $this->export();

            return $this->render('AdminBundle:Page:adminDashboard.html.twig', array(
                'listUsers' => $listeUsers,
                'mode' => $mode,
                'breadcrumb' => $bc
            ));
        }
    }

    /**
     * @Route("/page", name="admin_page_listing")
     */
    public function listPageAction(Request $request)
    {
        //
        // CREATION DU FIL D'ARIANE
        //
        $path = $request->getPathInfo();
        $breadcrumb= new Breadcrumb();
        $bc = $breadcrumb->getBreadcrumb($path);
        //
        // END CREATION DU FIL D'ARIANE
        //
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository('PageBundle:Page')->findAll();

        return $this->render('AdminBundle:Page:pageList.html.twig', array(
            'pages' => $pages,
            'breadcrumb' => $bc,
        ));
    }

    /**
     * @Route("/page/add", name="admin_page_add")
     */
    public function addPageAction(Request $request)
    {
        //
        // CREATION DU FIL D'ARIANE
        //
        $path = $request->getPathInfo();
        $breadcrumb= new Breadcrumb();
        $bc = $breadcrumb->getBreadcrumb($path);
        //
        // END CREATION DU FIL D'ARIANE
        //

        $blocTypes = $this->getParameter("blocType");
        $page = new Page();
        $form = $this->createForm(PageType::class, $page, array("bloc_types" => $blocTypes));
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $multibloc = $em->getRepository(Bloc::class)->findBy(array('page' => null));
        $bloc = new Bloc();
        $blocs = [];

        foreach( $multibloc as $bloc)
        {
            $blocs[$bloc->getTitle()] = $bloc->getTitle();
        }

        $formMultiBloc = $this->createForm(MultiBlocsType::class, array("multi_blocs" => $blocs));

        if ($form->isSubmitted() && $form->isValid())
        {
            // DATA FORM_MULTI_BLOC
            if(!empty($request->request->get('multi_blocs')))
            {

                foreach($request->request->get('multi_blocs') as $bloc)
                {
                    //récupération du modèle sélectionné
                    foreach($bloc as $array)
                    {
                        $titleBloc = $array['title'];
                        $typeBloc = $array['typeBloc'];
                        $numOrder = $array['num_order'];

                        //récupération du bloc modèle
                        $modelBloc = $em->getRepository(Bloc::class)->findOneBy(array('title' => $titleBloc));
                        //création d'un nouveau bloc
                        $newBloc = new Bloc();
                        //set des données du modèle dans le nouveau bloc
                        $newBloc->setContent($modelBloc->getContent());
                        $newBloc->setTitle($modelBloc->getTitle());

                        $typeBloc != "" ? $newBloc->setTypeBloc($typeBloc) : $newBloc->setTypeBloc(0);
                        $numOrder != "" ? $newBloc->setNumOrder($numOrder) : $newBloc->setNumOrder(0);

                        $newBloc->setPage($page);

                        $em->persist($newBloc);
                    }
                }
            }
            //END
            $em->persist($page);
            $em->flush();
            $this->addFlash('success', 'Page ajoutée');
            return $this->redirectToRoute('admin_page_listing');
        }

        return $this->render('AdminBundle:Form:formPage.html.twig', array(
            'form' => $form->createView(),
            'page' => $page,
            'formMultiBloc' => $formMultiBloc->createView(),
            'breadcrumb' => $bc
        ));
    }

    /**
     * @Route("/page/show/{slug}", name="admin_page_show")
     */
    public function showPageAction(Page $page, Request $request)
    {
        //
        // CREATION DU FIL D'ARIANE
        //
        $path = $request->getPathInfo();
        $slug = $page->getSlug();
        $breadcrumb= new Breadcrumb();
        $bc = $breadcrumb->getBreadcrumb($path, $slug);
        //dump($bc); die;
        //
        // END CREATION DU FIL D'ARIANE
        //

        $blocLabels = array_flip($this->getParameter("blocType"));
        //dump($blocLabels); die;
        return $this->render('AdminBundle:Page:showPage.html.twig', array(
            'page' => $page,
            'blocLabels' => $blocLabels,
            'breadcrumb' => $bc,
            'slug' => $slug,
        ));
    }

    /**
     * @Route("/page/publish/{slug}", name="admin_page_publish")
     */
    public function showPagePublish(Page $page, Request $request)
    {
        $blocLabels = array_flip($this->getParameter("blocType"));
        //dump( $blocLabels);die;
        $em = $this->getDoctrine()->getManager();
        $bloc = $page->getBlocs()->get($request->query->get('id'));
        if ($bloc->getPublished() == 1)
            $bloc->setPublished(0);
        else
            $bloc->setPublished(1);
        $em->persist($page);
        $em->flush();
        return $this->redirectToRoute('admin_page_show', array('slug' => $page->getSlug()));
    }

    /**
     * @Route("/page/edit/{slug}", name="admin_page_edit")
     */
    public function editPageAction(Page $page, Request $request)
    {
        //
        // CREATION DU FIL D'ARIANE
        //
        $path = $request->getPathInfo();
        $slug = $page->getSlug();
        $breadcrumb= new Breadcrumb();
        $bc = $breadcrumb->getBreadcrumb($path, $slug);
        //
        // END CREATION DU FIL D'ARIANE
        //
        $blocTypes = $this->getParameter("blocType");
        //dump($blocTypes);die;
        $form = $this->createForm(PageType::class, $page, array("bloc_types" => $blocTypes));

        $em = $this->getDoctrine()->getManager();

        // RECHERCHE DES BLOCS COMMUNS EXISTANTS
        $multibloc = $em->getRepository(Bloc::class)->findBy(array('page' => null));
        // STOCKAGE DE LEUR TITRE DANS UN ARRAY
        $blocs = [];
        foreach( $multibloc as $bloc)
        {
           $blocs[$bloc->getTitle()] = $bloc->getTitle();
        }
        //FORMULAIRE MULTI_BLOC
        $bloc = new Bloc();

        $formMultiBloc = $this->createForm(MultiBlocsType::class, array("multi_blocs" => $blocs));

        $id = $page->getUpfile()->getId();
        $files = $em->getRepository("UploadBundle:Files")->find($id);
        $image = $files->getImage();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // DATA FORM_MULTI_BLOC
            if(!empty($request->request->get('multi_blocs')))
            {

                foreach($request->request->get('multi_blocs') as $bloc) {
                    //récupération du modèle sélectionné
                    foreach($bloc as $array) {

                        $titleBloc = $array['title'];
                        $typeBloc = $array['typeBloc'];
                        $numOrder = $array['num_order'];

                        //récupération du bloc modèle
                        $modelBloc = $em->getRepository(Bloc::class)->findOneBy(array('title' => $titleBloc));
                        //création d'un nouveau bloc
                        $newBloc = new Bloc();
                        //set des données du modèle dans le nouveau bloc
                        $newBloc->setContent($modelBloc->getContent());
                        $newBloc->setTitle($modelBloc->getTitle());

                        $typeBloc != "" ? $newBloc->setTypeBloc($typeBloc) : $newBloc->setTypeBloc(0);
                        $numOrder != "" ? $newBloc->setNumOrder($numOrder) : $newBloc->setNumOrder(0);

                        $newBloc->setPage($page);

                        $em->persist($newBloc);
                    }
                }
            }
            //END

            if ($page->getUpfile()->getImage() == null)
            {
                $page->getUpfile()->setImage($image);
            }

            $em->persist($page);
            $em->flush();
            
            $this->addFlash('success', 'Page modifiée');

            return $this->redirectToRoute('admin_page_show', array("slug" => $page->getSlug()));
        }

        return $this->render('AdminBundle:Form:formPage.html.twig', array(
            'form' => $form->createView(),
            'formMultiBloc' => $formMultiBloc->createView(),
            'page' => $page,
            'multibloc' => $multibloc,
            'breadcrumb' => $bc,
            'slug' => $slug
        ));
    }

    /**
     * @Route("/page/delete/{slug}", name="admin_page_delete")
     */
    public function deletePageAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $page->getCategory();
        foreach ($category as $currentcategory)
        {
            $currentcategory->removePage($page);
        }
        //
        // BEGIN
        // We have to do this because a category can point to a page without the page knowing it.
        //
        $allCategory = $em->getRepository("PageBundle:Category")->findAll();
        foreach($allCategory as $currentcategory)
        {
            if ($currentcategory->getMainpage() != null && $currentcategory->getMainpage()->getId() == $page->getId())
            {
                $currentcategory->setMainpage(null);
            }
        }
        //
        // END
        //
        $em->remove($page);
        $em->flush();

        $this->addFlash('success', 'Page supprimée');

        return $this->redirectToRoute('admin_page_listing');
    }

    /**
     * @Route("/maintenance", name="admin_setup_maintenance")
     */
    public function maintenanceStatus()
    {
        $mode = file_get_contents(__DIR__.'/../Resources/config/maintenance.lock');
        $file = fopen(__DIR__.'/../Resources/config/maintenance.lock', 'w+');
        $mode == 'true' ? fwrite($file, "false") : fwrite($file, "true");
        $mode == 'true' ? $this->addFlash('warning', 'Maintenance arrêtée') : $this->addFlash('success', 'Maintenance lancée');
        return $this->redirectToRoute('admin_dashboard');
    }

    public function export()
    {
        $bundles = $this->container->getParameter('kernel.bundles');
        foreach ($bundles as $myBundle)
        {
            if ($myBundle == 'kapla\ProgrammeBundle\ProgrammeBundle')
                {
        $em = $this->getDoctrine()->getManager();

        $participants = $em->getRepository('ProgrammeBundle:Participant')->findAll();

        $handle = fopen(__DIR__.'/../../../../web/downloads/output.csv', 'w+');

        fwrite($handle,"Nom; Prenom; Email; Tel; Date anniversaire; Ecole; Filiere; Diplome; Tournoi; langage; Streaming; Connait ACENSI; Pseudo; Date Inscription;\n");
        foreach ($participants as $results){
            $tournoi = "";
            $tournoi = $results->getTournois()->getName();
            if ($results->getIsKnow() == 0){
                $isknow = 'Non';
            }else{
                $isknow = 'Oui';
            }
            if($results->getStream() == 0){
                $stream = 'Non';
            }elseif ($results->getStream() == 1){
                $stream ='Oui';
            }else{
                $stream = 'Ne sait pas';
            }
            $str = $results->getLastname().";". $results->getFirstname().";". $results->getEmail().";"."=\"".$results->getTel()."\";". $results->getBirthday()->format('d-m-Y').";" .$results->getSchool()->getName().";". $results->getFiliere().";". $results->getDiplome()->format('d-m-Y').";".$tournoi.";".$results->getLangage().";".$stream.";".$isknow.";".$results->getPseudo().";".$results->getCreatedAt()->format('d-m-Y').";\n";
            $str = mb_convert_encoding($str, "ISO-8859-1");
            fwrite($handle, $str);

        }

        fclose($handle);
            }
        }
    }

}

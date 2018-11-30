<?php
namespace kapla\admin\Controller;

use kapla\page\Entity\Bloc;
use kapla\page\Entity\CategoryList;
use kapla\page\Entity\Content;
use kapla\page\Form\BlocType;
use kapla\page\Form\ContentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use kapla\page\Entity\Category;
use kapla\page\Form\CategoryListType;
use kapla\page\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use kapla\admin\Menu\Breadcrumb;

class AdminContentController extends Controller
{
    /**
     * @Route("/content", name="admin_content_listing")
     */
    public function indexContentAction(Request $request)
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
        $bloc = $em->getRepository(Bloc::class)->findBy(array('page' => null));
        return $this->render('AdminBundle:Content:contentList.html.twig', array(
            'bloc' => $bloc,
            'breadcrumb' => $bc
        ));
    }
    /**
     * @Route("/content/add", name="admin_content_add")
     */
    public function addContentAction(Request $request)
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
        $bloc = new Bloc;
        $form = $this->createForm(BlocType::class, $bloc, array('bloc_types' => $blocTypes));
        $form->handleRequest($request);

        if($form['num_order']->getData() == null && $form['typeBloc']->getData() == null )
        {
            $form['num_order']->setData(0);
            $form['typeBloc']->setData(0);
        }




        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            if(!empty($em->getRepository(Bloc::class)->findBy(array('title' => $bloc->getTitle()))))
            {
                $this->addFlash('error', 'Ce titre est déjà utilisé par un autre bloc');
                return $this->render('AdminBundle:Form:formContent.html.twig', array(
                    'form' => $form->createView(),
                    'bloc' => $bloc,
                    'breadcrumb' => $bc
                ));
            }
            $em->persist($bloc);
            $em->flush();
            $this->addFlash('success', 'Contenu ajouté');
            return $this->redirectToRoute('admin_content_listing');
        }

        return $this->render('AdminBundle:Form:formContent.html.twig', array(
            'form' => $form->createView(),
            'bloc' => $bloc,
            'breadcrumb' => $bc
        ));
    }
    /**
     * @Route("/content/delete/{id}", name="admin_content_delete")
     */
    public function deleteContentAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $bloc = $em->getRepository(Bloc::class)->find($id);
        $contentId = $bloc->getContent()->getId();
        $blocs = $em->getRepository(Bloc::class)->findBy(array('content' => $contentId));

        foreach($blocs as $bloc)
        {
            $em->remove($bloc);
        }

        $em->flush();

        $this->addFlash('success', 'Le modèle a bien été supprimé');

        return $this->redirectToRoute('admin_content_listing');
    }
}
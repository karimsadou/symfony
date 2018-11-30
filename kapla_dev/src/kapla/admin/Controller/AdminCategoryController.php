<?php

namespace kapla\admin\Controller;

use kapla\page\Entity\CategoryList;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use kapla\page\Entity\Category;
use kapla\page\Form\CategoryListType;
use kapla\page\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use kapla\admin\Menu\Breadcrumb;


class AdminCategoryController extends Controller
{

    /**
     * @Route("/category", name="admin_category_listing")
     */
    public function listcategoryAction(Request $request)
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
        $categories = $em->getRepository('PageBundle:Category')->findAll();
        return $this->render('AdminBundle:Category:categoryList.html.twig', array(
            'categories' => $categories,
            'breadcrumb' => $bc
        ));
    }

    /**
     * @Route("/category/sort", name="admin_category_sort")
     */
    public function sortAction(Request $request)
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
        $temp = $em->getRepository('PageBundle:Category')->findBy(array(), array('num_order'=>'ASC'));

        $categories = new CategoryList();
        $categories->setCategories($temp);

        $form = $this->createForm(CategoryListType::class, $categories);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($categories->getCategories() as $category) {
                $em->persist($category);
            }
            $em->flush();
            return $this->redirectToRoute('admin_category_listing');
        }

        $i = 0;
        foreach ($categories->getCategories() as $categ) {
            $nameToId[$categ->getName()] = $i;
            $i++;
        }
        return $this->render('AdminBundle:Category:categorySort.html.twig', array(
            'form' => $form->createView(),
            'categories' => $categories,
            'nameToId' => json_encode($nameToId),
            'breadcrumb' => $bc
        ));
    }

    /**
     * @Route("/category/add", name="admin_category_add")
     */
    public function addCategoryAction(Request $request)
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

        $category = new Category();
        $category->setNumOrder(0);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Catégorie créée');

            return $this->redirectToRoute('admin_category_listing');
        }

        return $this->render('AdminBundle:Form:formCategory.html.twig', array(
            'form' => $form->createView(),
            'category' => $category,
            'breadcrumb' => $bc
        ));
    }
    
    /**
     * @Route("/category/edit/{slug}", name="admin_category_edit")
     */
    public function editCategoryAction(Category $category, Request $request)
    {
        //
        // CREATION DU FIL D'ARIANE
        //
        $path = $request->getPathInfo();
        $slug = $category->getSlug();
        $breadcrumb= new Breadcrumb();
        $bc = $breadcrumb->getBreadcrumb($path, $slug);
        //
        // END CREATION DU FIL D'ARIANE
        //

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Catégorie modifiée');
            return $this->redirectToRoute('admin_category_listing');
        }

        return $this->render('AdminBundle:Form:formCategory.html.twig', array(
            'form' => $form->createView(),
            'category' => $category,
            'breadcrumb' => $bc,
            'slug' => $slug
        ));
    }

    /**
     * @Route("/category/delete/{slug}", name="admin_category_delete")
     */
    public function deleteCategoryAction(Category $category)
    {
        $parent = $category->getParent();
        foreach ($category->getChildren() as $children)
        {
            $children->setParent($parent);
        }

        $em = $this->getDoctrine()->getManager();
        $allPage = $em->getRepository('PageBundle:Page')->findAll();
        foreach($allPage as $page)
        {
            if ($page->getCategory() == $category)
            {
                $page->setCategory($parent);
            }
        }
        $category->setParent(null);
        $category->setMainpage(null);
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', 'Catégorie supprimée');
        return $this->redirectToRoute('admin_category_listing');
    }
}

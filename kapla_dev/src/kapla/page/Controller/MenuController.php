<?php

namespace kapla\page\Controller;

use kapla\page\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use kapla\page\Entity\Page;

class MenuController extends Controller
{
    //Category sans parent order by id
    public function listAction($slug, $static = false)
    {

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('PageBundle:Category');
        $isHome = false;
        if ($static == false)
        {
            $page = $em->getRepository('PageBundle:Page')->findOneBy(['slug' => $slug]);
            if ($rep->getMinNumOrder())
            {
                $home = $rep->getMinNumOrder()[0];
                    if ($home->getMainPage() == $page)
                    {
                        $isHome = true;
                    }
            }           
        }


        $categories = $rep->findBy(['parent' => null], ['num_order' => 'ASC']);
        return $this->render('PageBundle:Menu:menu.html.twig', array(
            'categories' => $categories,
            'isHome' => $isHome
        ));
    }

}
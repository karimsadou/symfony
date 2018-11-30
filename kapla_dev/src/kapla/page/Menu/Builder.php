<?php

namespace kapla\page\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->addChild('Accueil', array('route' => 'homepage'));

        // access services from the container!
        $em = $this->container->get('doctrine')->getManager();
        // findMostRecent and Blog are just imaginary examples
        $categories = $em->getRepository('PageBundle:Category')->findAll();

        foreach($categories as $category)
        {
            $menu->addChild($category->getName(), array(
                'route' => 'homepage'
            ));
        }

        // create another menu item
        $menu->addChild('About Me', array('route' => 'homepage'));
        // you can also add sub level's to your menu's as follows
        $menu['About Me']->addChild('Edit profile', array('route' => 'homepage'));

        // ... add more children

        return $menu;
    }
}
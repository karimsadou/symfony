<?php

namespace kapla\admin\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->addChild("Dashboard ", array('route' => 'admin_dashboard'));
        $menu->addChild("Pages ", array('route' => 'admin_page_listing'));
        $menu->addChild("Categories", array('route' => 'admin_category_listing'));
        $menu->addChild("Contenu", array('route' => 'admin_category_listing'));
        $menu->setChildrenAttributes(array('class' => 'navbar-nav mr-auto'));

        return $menu;
    }
}
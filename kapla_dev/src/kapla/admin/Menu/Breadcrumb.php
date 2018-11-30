<?php
/**
 * Created by PhpStorm.
 * User: angelina.monate
 * Date: 19/09/2018
 * Time: 12:56
 */

namespace kapla\admin\Menu;


class Breadcrumb
{

    public function getBreadcrumb($path, $slug = 0, $id = 0, $page = 0)
    {//var_dump('/admin/'. $page . '/richtext/edit/' . $id);
        switch ($path)
        {
            case '/admin/':
                {
                    $bc = [
                        ['name' => 'Dashboard', 'route' => 'admin_dashboard']
                    ];
                    return $bc;
                    break;
                }
            case '/admin/page':
                {
                    $bc = [
                        ['name' => 'Dashboard', 'route' => 'admin_dashboard'],
                        ['name' => 'Pages', 'route' => 'admin_page_listing']
                    ];
                    return $bc;
                    break;
                }
            case '/admin/page/show/' . $slug :
                {
                    $bc = [
                        ['name' => 'Dashboard', 'route' => 'admin_dashboard'],
                        ['name' => 'Pages', 'route' => 'admin_page_listing']
                    ];
                    return $bc;
                    break;
                }
            case '/admin/page/add':
                {
                    $bc = [
                        ['name' => 'Dashboard', 'route' => 'admin_dashboard'],
                        ['name' => 'Pages', 'route' => 'admin_page_listing'],
                        ['name' => 'Nouvelle Page', 'route' => 'admin_page_add']
                    ];
                    return $bc;
                    break;
                }
            case '/admin/page/edit/' . $slug:
                {
                    $bc = [
                        ['name' => 'Dashboard', 'route' => 'admin_dashboard'],
                        ['name' => 'Pages', 'route' => 'admin_page_listing'],
                    ];
                    return $bc;
                    break;
                }
            case '/admin/'. $page . '/richtext/edit/' . $id:
                {
                    $bc = [
                        ['name' => 'Dashboard', 'route' => 'admin_dashboard'],
                        ['name' => 'Pages', 'route' => 'admin_page_listing'],
                    ];
                    return $bc;
                    break;
                }
            case '/admin/category':
                {
                    $bc = [
                        ['name' => 'Dashboard', 'route' => 'admin_dashboard'],
                        ['name' => 'Catégories', 'route' => 'admin_category_listing'],
                    ];
                    return $bc;
                    break;
                }
            case '/admin/category/add':
                {
                    $bc = [
                        ['name' => 'Dashboard', 'route' => 'admin_dashboard'],
                        ['name' => 'Catégories', 'route' => 'admin_category_listing'],
                        ['name' => 'Nouvelle catégorie', 'route' => 'admin_category_add']
                    ];
                    return $bc;
                    break;
                }
            case '/admin/category/edit/' . $slug:
                {
                    $bc = [
                        ['name' => 'Dashboard', 'route' => 'admin_dashboard'],
                        ['name' => 'Catégories', 'route' => 'admin_category_listing'],
                    ];
                    return $bc;
                    break;
                }
            case '/admin/category/sort':
                {
                    $bc = [
                        ['name' => 'Dashboard', 'route' => 'admin_dashboard'],
                        ['name' => 'Catégories', 'route' => 'admin_category_listing'],
                        ['name' => 'Organisation', 'route' => 'admin_category_sort']
                    ];
                    return $bc;
                    break;
                }
            case '/admin/'. $page . '/gallery/edit/' . $id :
                {
                    $bc = [
                        ['name' => 'Dashboard', 'route' => 'admin_dashboard'],
                        ['name' => 'Pages', 'route' => 'admin_page_listing'],
                    ];
                    return $bc;
                    break;
                }
            case "/admin/" . $page . "/pagelist/edit/" . $id:
                {
                    $bc = [
                        ['name' => 'Dashboard', 'route' => 'admin_dashboard'],
                        ['name' => 'Pages', 'route' => 'admin_page_listing'],
                    ];
                    return $bc;
                    break;
                }
            case "/admin/" . $page . "/video/edit/" . $id:
                {
                    $bc = [
                        ['name' => 'Dashboard', 'route' => 'admin_dashboard'],
                        ['name' => 'Pages', 'route' => 'admin_page_listing'],
                    ];
                    return $bc;
                    break;
                }
            case "/admin/content":
                {
                    $bc = [
                        ['name' => 'Dashboard', 'route' => 'admin_dashboard'],
                        ['name' => 'Modèles', 'route' => 'admin_content_listing'],
                    ];
                    return $bc;
                    break;
                }
            case "/admin/content/add":
                {
                    $bc = [
                        ['name' => 'Dashboard', 'route' => 'admin_dashboard'],
                        ['name' => 'Modèles', 'route' => 'admin_content_listing'],
                        ['name' => 'Nouveau modèle', 'route' => 'admin_content_add']
                    ];
                    return $bc;
                    break;
                }
        }
        return false;
    }
}